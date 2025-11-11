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
        Schema::create('university_criteria_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('e.g., "Accepts Backlogs", "Minimum GPA"');
            $table->string('slug')->unique()->comment('e.g., "accepts_backlogs", "minimum_gpa"');
            $table->string('type')->default('boolean')->comment('boolean, number, decimal, text, json');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_criteria_fields');
    }
};
