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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('details')->nullable();
            $table->string('banner_image')->nullable();
            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('study_level_id')->nullable();
            $table->unsignedBigInteger('funding_type')->nullable();
            $table->dateTime('application_start_date')->nullable();
            $table->dateTime('application_end_date')->nullable();
            $table->dateTime('offers_received_from_date')->nullable();
            $table->integer('available_award_number')->nullable();
            $table->tinyInteger('status')->default(STATUS_DEACTIVATE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
