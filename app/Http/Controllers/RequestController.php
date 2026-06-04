<?php

namespace App\Http\Controllers;

use App\Models\Request as MaterialRequest;
use App\Models\Job;
use App\Models\Material;
use App\Models\Department;
use App\Models\JobBom;
use App\Models\DepartmentInventory;
use App\Models\RequestStatusHistory;
use App\Models\StockMovement;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = MaterialRequest::with(['job', 'department', 'user', 'items.material']);

        if (!$user->isWarehouse() && !$user->isAdmin()) {
            $query->where('department_id', $user->department_id);
        }


        $jobsData = Job::all();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->has('job')) {
            $query->where('job_id', $request->job);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhereHas('job', function ($jobQuery) use ($search) {
                        $jobQuery->where('job_number', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->latest()->paginate(15);
        $departments = Department::where('is_active', true)->get();
        $jobs = Job::whereIn('status', ['planned', 'in_progress'])->get();

        return view('requests.index', compact('requests', 'departments', 'jobs', 'jobsData'));
    }

    public function create()
    {
        $user = auth()->user();
        $materials = Material::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        // Get jobs that can be requested
        $jobs = Job::whereIn('status', ['planned', 'in_progress'])
            ->with('boms')
            ->latest()
            ->get();

        return view('requests.create', compact('materials', 'departments', 'jobs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'department_id' => 'required|exists:departments,id',
            'request_date' => 'required|date',
            'request_type' => 'required|in:normal,additional',
            // 'additional_reason' => 'required_if:request_type,additional|nullable|string',
            'notes' => 'nullable|string',
            // 'items' => 'required|array|min:1',
            // 'items.*.job_bom_id' => 'nullable|exists:job_boms,id',
            // 'items.*.material_id' => 'required|exists:materials,id',
            // 'items.*.quantity' => 'required|numeric|min:0.01',
            // 'items.*.is_additional' => 'nullable|boolean',
            // 'items.*.additional_reason' => 'required_if:items.*.is_additional,true|nullable|string',
            // 'items.*.specifications' => 'nullable|string',
            // 'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $materialRequest = MaterialRequest::create([
                'job_id' => $validated['job_id'],
                'department_id' => $validated['department_id'],
                'user_id' => auth()->id(),
                'request_date' => $validated['request_date'],
                'request_type' => $validated['request_type'],
                'additional_reason' => $validated['additional_reason'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft'
            ]);

            $jobBoms = JobBom::where('job_id', '=', $validated['job_id'])
                ->where('department_id','=',$validated['department_id'])
                ->get();

            // Check stock availability before creating items
            $stockIssues = [];
            foreach ($jobBoms as $item) {
                $material = Material::find($item['material_id']);
                if ($material->current_stock < $item['quantity_required']) {
                    $stockIssues[] = "{$material->name}: tersedia {$material->current_stock} {$material->unit}, diminta {$item['quantity_required']} {$material->unit}";
                }
            }

            if (!empty($stockIssues)) {
                DB::rollBack();
                $message = "Stok tidak cukup:\n" . implode("\n", $stockIssues);
                return back()->withInput()
                    ->with('error', $message);
            }

            foreach ($jobBoms as $item) {
                $material = Material::find($item['material_id']);

                $materialRequest->items()->create([
                    'job_bom_id' => $item['job_bom_id'] ?? null,
                    'material_id' => $item['material_id'],
                    'quantity_requested' => $item['quantity_required'],
                    'unit' => $material->unit,
                    'is_additional' => $item['is_additional'] ?? false,
                    'additional_reason' => $item['additional_reason'] ?? null,
                    'specifications' => $item['specifications'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $materialRequest->statusHistory()->create([
                'status' => 'draft',
                'user_id' => auth()->id(),
                'notes' => 'Request created'
            ]);

            DB::commit();

            return redirect()->route('requests.show', $materialRequest)
                ->with('success', 'Request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create request: ' . $e->getMessage());
        }
    }


    public function show(MaterialRequest $request)
    {
        $request->load([
            'job',
            'department',
            'user',
            'approvedBy',
            'completedBy',
            'items.material',
            'statusHistory.user'
        ]);

        return view('requests.show', compact('request'));
    }

    public function print(MaterialRequest $request)
    {
        $request->load([
            'job',
            'department',
            'user',
            'approvedBy',
            'completedBy',
            'items.material',
        ]);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('requests.print', compact('request'))->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'purchase-order-' . $request->request_number . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function submit(MaterialRequest $request)
    {
        if ($request->status !== 'draft') {
            return back()->with('error', 'Only draft requests can be submitted.');
        }

        DB::beginTransaction();
        try {
            $request->update(['status' => 'submitted']);

            $request->statusHistory()->create([
                'status' => 'submitted',
                'user_id' => auth()->id(),
                'notes' => 'Request submitted for approval'
            ]);

            DB::commit();

            return redirect()->route('requests.show', $request)
                ->with('success', 'Request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function approve(Request $httpRequest, MaterialRequest $request)
    {
        if (!auth()->user()->isWarehouse() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $httpRequest->validate([
            'items' => 'required|array',
            'items.*.quantity_approved' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Check if stock is still available before approval
            $stockIssues = [];
            foreach ($validated['items'] as $itemId => $itemData) {
                $item = $request->items()->find($itemId);
                $material = $item->material;

                if ($itemData['quantity_approved'] > 0 && $material->current_stock < $itemData['quantity_approved']) {
                    $stockIssues[] = "{$material->name}: tersedia {$material->current_stock} {$material->unit}, disetujui {$itemData['quantity_approved']} {$material->unit}";
                }
            }

            if (!empty($stockIssues)) {
                DB::rollBack();
                $message = "Stok tidak cukup untuk approve:\n" . implode("\n", $stockIssues);
                return back()->with('error', $message);
            }

            $hasPartial = false;
            foreach ($validated['items'] as $itemId => $itemData) {
                $item = $request->items()->find($itemId);
                $item->update([
                    'quantity_approved' => $itemData['quantity_approved']
                ]);

                if ($itemData['quantity_approved'] < $item->quantity_requested) {
                    $hasPartial = true;
                }

                // Update stock
                $material = $item->material;
                $stockBefore = $material->current_stock;
                $material->current_stock -= $itemData['quantity_approved'];
                $material->save();

                // Record stock movement
                StockMovement::create([
                    'material_id' => $material->id,
                    'type' => 'out',
                    'quantity' => $itemData['quantity_approved'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $material->current_stock,
                    'request_id' => $request->id,
                    'user_id' => auth()->id(),
                    'movement_date' => now(),
                    'notes' => 'Material request approved - Job: ' . $request->job->job_number
                ]);

                // Sync to department inventory (add stock to department)
                if ($request->department_id && $itemData['quantity_approved'] > 0) {
                    $inventory = DepartmentInventory::firstOrCreate(
                        [
                            'department_id' => $request->department_id,
                            'material_id' => $material->id,
                        ],
                        [
                            'current_stock' => 0,
                            'unit' => $material->unit ?? 'kg',
                            'status' => 'active',
                        ]
                    );

                    // Update department inventory stock
                    $inventory->increment('current_stock', $itemData['quantity_approved']);
                    $inventory->update(['last_counted_at' => now()]);
                }
            }

            $status = $hasPartial ? 'partial_approved' : 'approved';
            $request->update([
                'status' => $status,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            $request->statusHistory()->create([
                'status' => $status,
                'user_id' => auth()->id(),
                'notes' => 'Request approved by warehouse'
            ]);

            DB::commit();

            return redirect()->route('requests.show', $request)
                ->with('success', 'Request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function reject(Request $httpRequest, MaterialRequest $request)
    {
        if (!auth()->user()->isWarehouse() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $httpRequest->validate([
            'rejection_reason' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $request->update([
                'status' => 'rejected',
                'notes' => $validated['rejection_reason']
            ]);

            $request->statusHistory()->create([
                'status' => 'rejected',
                'user_id' => auth()->id(),
                'notes' => $validated['rejection_reason']
            ]);

            DB::commit();

            return redirect()->route('requests.show', $request)
                ->with('success', 'Request rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    public function complete(MaterialRequest $request)
    {
        if (!in_array($request->status, ['approved', 'partial_approved', 'ready_for_pickup'])) {
            return back()->with('error', 'Request cannot be completed.');
        }

        DB::beginTransaction();
        try {
            $request->update([
                'status' => 'completed',
                'completed_by' => auth()->id(),
                'completed_at' => now()
            ]);

            $request->statusHistory()->create([
                'status' => 'completed',
                'user_id' => auth()->id(),
                'notes' => 'Materials picked up'
            ]);

            DB::commit();

            return redirect()->route('requests.show', $request)
                ->with('success', 'Request completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete request: ' . $e->getMessage());
        }
    }
}
