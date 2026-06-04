<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            // Wax Room Materials
            [
                'code' => 'WAX-001',
                'name' => 'Injection Wax Grade A',
                'description' => 'High quality injection wax for precision casting',
                'type' => 'Material',
                'unit' => 'kg',
                'unit_price' => 150000,
                'min_stock' => 50,
                'max_stock' => 200,
                'current_stock' => 120,
                'lead_time_days' => 7,
                'storage_location' => 'A-01',
                'supplier' => 'Wax Supplier Co.',
                'is_active' => true,
                'specifications' => [
                    ['spec_name' => 'Melting Point', 'spec_value' => '65-70°C'],
                    ['spec_name' => 'Viscosity', 'spec_value' => '150-200 cPs'],
                ]
            ],
            [
                'code' => 'WAX-002',
                'name' => 'Pattern Wax Blue',
                'description' => 'Blue colored pattern wax',
                'type' => 'Material',
                'unit' => 'kg',
                'unit_price' => 125000,
                'min_stock' => 30,
                'max_stock' => 150,
                'current_stock' => 85,
                'lead_time_days' => 7,
                'storage_location' => 'A-02',
                'supplier' => 'Wax Supplier Co.',
                'is_active' => true,
            ],

            // Mould Room Materials
            [
                'code' => 'CER-001',
                'name' => 'Ceramic Slurry Prime',
                'description' => 'Primary ceramic coating slurry',
                'type' => 'Material',
                'unit' => 'liter',
                'unit_price' => 85000,
                'min_stock' => 100,
                'max_stock' => 500,
                'current_stock' => 45,
                'lead_time_days' => 14,
                'storage_location' => 'B-01',
                'supplier' => 'Ceramic Materials Inc.',
                'is_active' => true,
                'specifications' => [
                    ['spec_name' => 'Mesh Size', 'spec_value' => '325 mesh'],
                    ['spec_name' => 'Binder Type', 'spec_value' => 'Colloidal Silica'],
                ]
            ],
            [
                'code' => 'CER-002',
                'name' => 'Zircon Sand Fine',
                'description' => 'Fine grade zircon sand for shell building',
                'type' => 'Material',
                'unit' => 'kg',
                'unit_price' => 45000,
                'min_stock' => 200,
                'max_stock' => 800,
                'current_stock' => 550,
                'lead_time_days' => 14,
                'storage_location' => 'B-02',
                'supplier' => 'Refractory Supply Ltd.',
                'is_active' => true,
            ],

            // Melting Materials
            [
                'code' => 'MET-001',
                'name' => 'Stainless Steel 316L',
                'description' => 'Stainless steel grade 316L ingots',
                'type' => 'Material',
                'unit' => 'kg',
                'unit_price' => 95000,
                'min_stock' => 500,
                'max_stock' => 2000,
                'current_stock' => 1200,
                'lead_time_days' => 21,
                'storage_location' => 'C-01',
                'supplier' => 'Metal Trading Co.',
                'is_active' => true,
                'specifications' => [
                    ['spec_name' => 'Carbon (C)', 'spec_value' => 'Max 0.030%'],
                    ['spec_name' => 'Chromium (Cr)', 'spec_value' => '16-18%'],
                    ['spec_name' => 'Nickel (Ni)', 'spec_value' => '10-14%'],
                    ['spec_name' => 'Molybdenum (Mo)', 'spec_value' => '2-3%'],
                ]
            ],
            [
                'code' => 'MET-002',
                'name' => 'Carbon Steel A36',
                'description' => 'Carbon steel grade A36',
                'type' => 'Material',
                'unit' => 'kg',
                'unit_price' => 25000,
                'min_stock' => 1000,
                'max_stock' => 5000,
                'current_stock' => 3500,
                'lead_time_days' => 21,
                'storage_location' => 'C-02',
                'supplier' => 'Metal Trading Co.',
                'is_active' => true,
            ],

            // Machining Tools
            [
                'code' => 'TL-001',
                'name' => 'Carbide End Mill 10mm',
                'description' => '10mm diameter carbide end mill',
                'type' => 'Tool',
                'unit' => 'pcs',
                'unit_price' => 250000,
                'min_stock' => 10,
                'max_stock' => 50,
                'current_stock' => 8,
                'lead_time_days' => 14,
                'storage_location' => 'D-01',
                'supplier' => 'Cutting Tools Supplier',
                'is_active' => true,
                'specifications' => [
                    ['spec_name' => 'Diameter', 'spec_value' => '10mm'],
                    ['spec_name' => 'Flutes', 'spec_value' => '4'],
                    ['spec_name' => 'Coating', 'spec_value' => 'TiAlN'],
                ]
            ],
            [
                'code' => 'TL-002',
                'name' => 'HSS Drill Bit Set',
                'description' => 'High speed steel drill bit set 1-13mm',
                'type' => 'Tool',
                'unit' => 'set',
                'unit_price' => 450000,
                'min_stock' => 5,
                'max_stock' => 20,
                'current_stock' => 12,
                'lead_time_days' => 7,
                'storage_location' => 'D-02',
                'supplier' => 'Cutting Tools Supplier',
                'is_active' => true,
            ],

            // Consumables
            [
                'code' => 'CON-001',
                'name' => 'Grinding Wheel 180mm',
                'description' => '180mm grinding wheel for angle grinder',
                'type' => 'Consumable',
                'unit' => 'pcs',
                'unit_price' => 35000,
                'min_stock' => 20,
                'max_stock' => 100,
                'current_stock' => 15,
                'lead_time_days' => 3,
                'storage_location' => 'E-01',
                'supplier' => 'Abrasives Inc.',
                'is_active' => true,
                'specifications' => [
                    ['spec_name' => 'Diameter', 'spec_value' => '180mm'],
                    ['spec_name' => 'Grit', 'spec_value' => '60'],
                ]
            ],
            [
                'code' => 'CON-002',
                'name' => 'Cutting Oil Premium',
                'description' => 'Premium grade cutting and cooling oil',
                'type' => 'Consumable',
                'unit' => 'liter',
                'unit_price' => 65000,
                'min_stock' => 50,
                'max_stock' => 200,
                'current_stock' => 120,
                'lead_time_days' => 7,
                'storage_location' => 'E-02',
                'supplier' => 'Lubricants Co.',
                'is_active' => true,
            ],
            [
                'code' => 'CON-003',
                'name' => 'Safety Gloves (Pair)',
                'description' => 'Heat resistant safety gloves',
                'type' => 'Consumable',
                'unit' => 'pair',
                'unit_price' => 45000,
                'min_stock' => 50,
                'max_stock' => 200,
                'current_stock' => 25,
                'lead_time_days' => 3,
                'storage_location' => 'E-03',
                'supplier' => 'Safety Equipment Co.',
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            $specs = $material['specifications'] ?? [];
            unset($material['specifications']);

            $mat = Material::create($material);

            if (!empty($specs)) {
                foreach ($specs as $spec) {
                    $mat->specifications()->create($spec);
                }
            }
        }
    }
}
