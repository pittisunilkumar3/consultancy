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
        Schema::create('student_service_order_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_service_order_id');
            $table->unsignedBigInteger('student_id');
            $table->string('name');
            $table->unsignedBigInteger('file');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_service_order_files');
    }
};
