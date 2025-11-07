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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('slug');
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('intro_video_type')->nullable();
            $table->string('intro_video')->nullable();
            $table->integer('duration');
            $table->decimal('price', 12, 2)->default(0);
            $table->text('course_benefits')->nullable();
            $table->date('start_date')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_point')->nullable();
            $table->longText('faqs');
            $table->longText('instructors');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
