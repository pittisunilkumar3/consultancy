<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_service_order_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_service_order_id'); // Reference to Student Service Order
            $table->longText('details'); // Note details
            $table->unsignedBigInteger('user_id'); // User who added the note
            $table->softDeletes(); // Adds deleted_at column for soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_service_order_notes');
    }
};
