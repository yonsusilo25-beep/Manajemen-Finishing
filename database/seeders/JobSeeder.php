<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    public function run()
    {
        // Get admin user (assuming first user is admin)
        $adminUser = User::first();

        $jobs = [
            [
                'job_number' => 'BATCH-001',
                'job_name' => 'Valve Body Production',
                'product_name' => 'Valve Body Type A',
                'quantity' => 100,
                'status' => 'planned',
                'start_date' => Carbon::now()->addDays(2),
                'due_date' => Carbon::now()->addDays(15),
                'notes' => 'High priority order for customer XYZ',
                'created_by' => $adminUser->id,
            ],
            [
                'job_number' => 'BATCH-002',
                'job_name' => 'Pump Housing Production',
                'product_name' => 'Centrifugal Pump Housing',
                'quantity' => 50,
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(10),
                'notes' => 'Customer ABC regular order',
                'created_by' => $adminUser->id,
            ],
            [
                'job_number' => 'BATCH-003',
                'job_name' => 'Flange Casting',
                'product_name' => 'Industrial Flange 150mm',
                'quantity' => 200,
                'status' => 'planned',
                'start_date' => Carbon::now()->addDays(7),
                'due_date' => Carbon::now()->addDays(30),
                'notes' => 'Large batch order',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
}
