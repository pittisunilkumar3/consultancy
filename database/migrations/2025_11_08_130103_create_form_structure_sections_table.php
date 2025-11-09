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
        Schema::create('form_structure_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('form_structures')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_collapsible')->default(true);
            $table->boolean('is_expanded_by_default')->default(true);
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['structure_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_structure_sections');
    }
};
