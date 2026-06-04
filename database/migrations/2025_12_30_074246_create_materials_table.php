<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // Part, Material, Consumable, Tool
            $table->string('unit'); // kg, pcs, liter, meter, dll
            $table->string('alternative_unit')->nullable();
            $table->decimal('conversion_factor', 10, 4)->nullable();
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->string('packing_unit')->nullable();
            $table->integer('capacity_per_pack')->nullable();
            $table->integer('lead_time_days')->default(0);
            $table->string('storage_location')->nullable();
            $table->string('supplier')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
