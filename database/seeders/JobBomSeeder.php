<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobBom;
use App\Models\Material;
use App\Models\Department;
use Illuminate\Database\Seeder;

class JobBomSeeder extends Seeder
{
    public function run()
    {
        // Get IDs
        $batch001 = Job::where('job_number', 'BATCH-001')->first();
        $waxRoom = Department::where('name', 'Wax Room')->first();
        $mouldRoom = Department::where('name', 'Mould Room')->first();
        $melting = Department::where('name', 'Melting')->first();

        // Materials
        $wax001 = Material::where('code', 'WAX-001')->first();
        $wax002 = Material::where('code', 'WAX-002')->first();
        $cer001 = Material::where('code', 'CER-001')->first();
        $cer002 = Material::where('code', 'CER-002')->first();
        $met001 = Material::where('code', 'MET-001')->first();
        $met002 = Material::where('code', 'MET-002')->first();
        $con001 = Material::where('code', 'CON-001')->first();
        $con002 = Material::where('code', 'CON-002')->first();

        // BOM for BATCH-001 (8 materials total)
        $boms = [
            // Wax Room (2 materials)
            [
                'job_id' => $batch001->id,
                'material_id' => $wax001->id,
                'department_id' => $waxRoom->id,
                'quantity_required' => 15.00,
                'unit' => $wax001->unit,
                'sequence' => 1,
                'notes' => 'Primary wax for pattern making'
            ],
            [
                'job_id' => $batch001->id,
                'material_id' => $wax002->id,
                'department_id' => $waxRoom->id,
                'quantity_required' => 8.50,
                'unit' => $wax002->unit,
                'sequence' => 2,
                'notes' => 'Blue pattern wax for detail work'
            ],

            // Mould Room (2 materials)
            [
                'job_id' => $batch001->id,
                'material_id' => $cer001->id,
                'department_id' => $mouldRoom->id,
                'quantity_required' => 50.00,
                'unit' => $cer001->unit,
                'sequence' => 3,
                'notes' => 'For shell building - 5 layers'
            ],
            [
                'job_id' => $batch001->id,
                'material_id' => $cer002->id,
                'department_id' => $mouldRoom->id,
                'quantity_required' => 100.00,
                'unit' => $cer002->unit,
                'sequence' => 4,
                'notes' => 'Stucco material for shell strength'
            ],

            // Melting (4 materials)
            [
                'job_id' => $batch001->id,
                'material_id' => $met001->id,
                'department_id' => $melting->id,
                'quantity_required' => 250.00,
                'unit' => $met001->unit,
                'sequence' => 5,
                'notes' => 'Main casting material - SS316L'
            ],
            [
                'job_id' => $batch001->id,
                'material_id' => $con002->id,
                'department_id' => $melting->id,
                'quantity_required' => 10.00,
                'unit' => $con002->unit,
                'sequence' => 6,
                'notes' => 'Cutting oil for machining operations'
            ],
            [
                'job_id' => $batch001->id,
                'material_id' => $con001->id,
                'department_id' => $melting->id,
                'quantity_required' => 20.00,
                'unit' => $con001->unit,
                'sequence' => 7,
                'notes' => 'For grinding and finishing'
            ],
            [
                'job_id' => $batch001->id,
                'material_id' => $met002->id,
                'department_id' => $melting->id,
                'quantity_required' => 50.00,
                'unit' => $met002->unit,
                'sequence' => 8,
                'notes' => 'Backup material - Carbon Steel A36'
            ],
        ];

        foreach ($boms as $bom) {
            JobBom::create($bom);
        }

        // BOM for BATCH-002 (simplified - 6 materials)
        $batch002 = Job::where('job_number', 'BATCH-002')->first();

        $boms002 = [
            [
                'job_id' => $batch002->id,
                'material_id' => $wax001->id,
                'department_id' => $waxRoom->id,
                'quantity_required' => 20.00,
                'unit' => $wax001->unit,
                'sequence' => 1,
            ],
            [
                'job_id' => $batch002->id,
                'material_id' => $cer001->id,
                'department_id' => $mouldRoom->id,
                'quantity_required' => 70.00,
                'unit' => $cer001->unit,
                'sequence' => 2,
            ],
            [
                'job_id' => $batch002->id,
                'material_id' => $met001->id,
                'department_id' => $melting->id,
                'quantity_required' => 180.00,
                'unit' => $met001->unit,
                'sequence' => 3,
            ],
        ];

        foreach ($boms002 as $bom) {
            JobBom::create($bom);
        }
    }
}
