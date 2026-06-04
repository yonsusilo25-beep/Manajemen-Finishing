<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('low_stock')) {
            $query->whereRaw('current_stock <= min_stock');
        }

        $materials = $query->paginate(15);

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:materials,code',
            'name' => 'required',
            'type' => 'required',
            'unit' => 'required',
            'unit_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
            'packing_unit' => 'required',
            'capacity_per_pack' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'specifications' => 'nullable|array',
            'specifications.*.name' => 'required_with:specifications',
            'specifications.*.value' => 'required_with:specifications',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        $material = Material::create($validated);

        if ($request->has('specifications')) {
            foreach ($request->specifications as $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    $material->specifications()->create([
                        'spec_name' => $spec['name'],
                        'spec_value' => $spec['value']
                    ]);
                }
            }
        }

        return redirect()->route('materials.index')
            ->with('success', 'Material created successfully.');
    }

    public function show(Material $material)
    {
        $material->load('specifications', 'stockMovements.user');
        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $material->load('specifications');
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'code' => 'required|unique:materials,code,' . $material->id,
            'name' => 'required',
            'type' => 'required',
            'unit' => 'required',
            'unit_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
             'packing_unit' => 'required',
            'capacity_per_pack' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }
            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($validated);

        if ($request->has('specifications')) {
            $material->specifications()->delete();
            foreach ($request->specifications as $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    $material->specifications()->create([
                        'spec_name' => $spec['name'],
                        'spec_value' => $spec['value']
                    ]);
                }
            }
        }

        return redirect()->route('materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}

