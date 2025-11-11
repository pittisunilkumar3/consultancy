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
        Schema::create('career_corner_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Student who submitted the form');
            $table->unsignedBigInteger('form_structure_id')->comment('Reference to the form structure');
            $table->json('form_data')->comment('JSON encoded form responses');
            $table->tinyInteger('status')->default(STATUS_ACTIVE)->comment('Submission status');
            $table->text('notes')->nullable()->comment('Admin notes or comments');
            $table->unsignedBigInteger('reviewed_by')->nullable()->comment('Admin who reviewed the submission');
            $table->timestamp('reviewed_at')->nullable()->comment('When the submission was reviewed');
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('form_structure_id')->references('id')->on('form_structures')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('form_structure_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_corner_submissions');
    }
};
