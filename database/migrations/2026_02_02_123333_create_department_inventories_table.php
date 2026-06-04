<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();

            // Stok Information
            // $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('kg');

            // Stok Management
            $table->integer('current_stock')->nullable();
            $table->integer('min_stock')->nullable();
            $table->integer('max_stock')->nullable();
            $table->integer('reorder_point')->nullable();
            $table->integer('reorder_quantity')->nullable();

            // Location & Status
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // // Financial
            // $table->decimal('unit_cost', 12, 2)->nullable();
            // $table->decimal('total_value', 12, 2)->nullable();

            // Tracking
            $table->timestamp('last_counted_at')->nullable();
            $table->timestamps();

            // Unique constraint: hanya 1 entry per department per material
            $table->unique(['department_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_inventories');
    }
};
