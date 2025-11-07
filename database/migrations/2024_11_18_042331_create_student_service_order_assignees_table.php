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
        Schema::create('student_service_order_assignees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_service_order_id'); // Reference to Student Service Order
            $table->unsignedBigInteger('assigned_to'); // Assignee user ID
            $table->unsignedBigInteger('assigned_by'); // Assigned by user ID
            $table->tinyInteger('is_active')->default(1); // Active status
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
        Schema::dropIfExists('student_service_order_assignees');
    }
};
