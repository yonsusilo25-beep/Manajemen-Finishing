<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'code' => 'DEV',
                'name' => 'Dev Engineer',
                'description' => 'Development Engineering Department',
                'is_active' => true
            ],
            [
                'code' => 'WAX',
                'name' => 'Wax Room',
                'description' => 'Wax Pattern Production',
                'is_active' => true
            ],
            [
                'code' => 'MLD',
                'name' => 'Mould Room',
                'description' => 'Mould Making Department',
                'is_active' => true
            ],
            [
                'code' => 'MLT',
                'name' => 'Melting',
                'description' => 'Metal Melting Department',
                'is_active' => true
            ],
            [
                'code' => 'CUT',
                'name' => 'Cut Off',
                'description' => 'Cutting Operations',
                'is_active' => true
            ],
            [
                'code' => 'FIN',
                'name' => 'Finishing & Straightening',
                'description' => 'Finishing Operations',
                'is_active' => true
            ],
            [
                'code' => 'MCH',
                'name' => 'Machining',
                'description' => 'Machining Operations',
                'is_active' => true
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
