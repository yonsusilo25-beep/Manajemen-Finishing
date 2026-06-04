<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained();
            $table->decimal('quantity_requested', 10, 2);
            $table->decimal('quantity_approved', 10, 2)->nullable();
            $table->string('unit');
            $table->text('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('job_bom_id')->nullable()->constrained('job_boms')->onDelete('cascade');
            $table->boolean('is_additional')->default(false);
            $table->text('additional_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_items');
    }
};
