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
        Schema::create('university_criteria_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('university_id');
            $table->unsignedBigInteger('criteria_field_id');
            $table->text('value')->nullable()->comment('Store as text/JSON (flexible for all types)');
            $table->timestamps();

            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
            $table->foreign('criteria_field_id')->references('id')->on('university_criteria_fields')->onDelete('cascade');
            $table->unique(['university_id', 'criteria_field_id'], 'uniq_university_criteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_criteria_values');
    }
};
