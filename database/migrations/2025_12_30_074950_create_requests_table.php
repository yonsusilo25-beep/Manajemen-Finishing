<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('request_date');
            $table->enum('urgency', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', [
                'draft',
                'submitted',
                'checking_stock',
                'approved',
                'partial_approved',
                'ready_for_pickup',
                'completed',
                'rejected',
                'cancelled'
            ])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
