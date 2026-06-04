<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Material;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('creator')->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        $materials = Material::orderBy('code')->get();
        $departments = Department::orderBy('name')->get();
        return view('jobs.create', compact('materials', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_number' => 'required|string|unique:jobs,job_number',
            'job_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'boms' => 'nullable|array',
            'boms.*.material_id' => 'required|exists:materials,id',
            'boms.*.department_id' => 'required|exists:departments,id',
            'boms.*.quantity_required' => 'required|numeric|min:0.01',
            'boms.*.unit' => 'required|string|max:50',
            'boms.*.notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        DB::beginTransaction();
        try {
            $job = Job::create($validated);

            // Create BOMs
            if ($request->has('boms') && is_array($request->boms)) {
                foreach ($request->boms as $index => $bomData) {
                    $job->boms()->create([
                        'material_id' => $bomData['material_id'],
                        'department_id' => $bomData['department_id'],
                        'quantity_required' => $bomData['quantity_required'],
                        'unit' => $bomData['unit'],
                        'sequence' => $index + 1,
                        'notes' => $bomData['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('jobs.index')->with('success', 'Job created successfully with BOMs.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to create job: ' . $e->getMessage());
        }
    }

    public function show(Job $job)
    {
        $job->load('creator', 'boms.material', 'boms.department');
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $job->load('boms.material', 'boms.department');
        $materials = Material::orderBy('code')->get();
        $departments = Department::orderBy('name')->get();
        return view('jobs.edit', compact('job', 'materials', 'departments'));
    }

    public function getRequests(Job $job)
    {
        $requests = $job->requests()->with(['department'])->get();
        return response()->json([
            'job' => $job,
            'requests' => $requests,
            'success' => true
        ]);
    }

    public function requestView(Job $job)
    {
        $requests = $job->requests()->paginate(5);
        return view('requests.show-partial',compact('requests'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'job_number' => 'required|string|unique:jobs,job_number,' . $job->id,
            'job_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'boms' => 'nullable|array',
            'boms.*.material_id' => 'required|exists:materials,id',
            'boms.*.department_id' => 'required|exists:departments,id',
            'boms.*.quantity_required' => 'required|numeric|min:0.01',
            'boms.*.unit' => 'required|string|max:50',
            'boms.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $job->update($validated);

            // Delete existing BOMs
            $job->boms()->delete();

            // Create new BOMs
            if ($request->has('boms') && is_array($request->boms)) {
                foreach ($request->boms as $index => $bomData) {
                    $job->boms()->create([
                        'material_id' => $bomData['material_id'],
                        'department_id' => $bomData['department_id'],
                        'quantity_required' => $bomData['quantity_required'],
                        'unit' => $bomData['unit'],
                        'sequence' => $index + 1,
                        'notes' => $bomData['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Failed to update job: ' . $e->getMessage());
        }
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    // API endpoint for getting job BOM
    public function getBom(Job $job, Request $request)
    {
        $departmentId = $request->get('department_id');

        if ($departmentId) {
            $boms = $job->boms()
                ->with(['material', 'department'])
                ->where('department_id', $departmentId)
                ->orderBy('sequence')
                ->get();
        } else {
            $boms = $job->boms()
                ->with(['material', 'department'])
                ->orderBy('sequence')
                ->get();
        }

        return response()->json([
            'job' => $job,
            'boms' => $boms,
            'urgency' => $job->auto_urgency,
        ]);
    }
}
