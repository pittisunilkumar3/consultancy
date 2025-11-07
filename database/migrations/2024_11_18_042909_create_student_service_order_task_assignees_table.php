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
        Schema::create('student_service_order_task_assignees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_task_id'); // Reference to Student Service Order Task
            $table->unsignedBigInteger('assign_to'); // Assignee user ID
            $table->unsignedBigInteger('assign_by'); // Assigning user ID
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
        Schema::dropIfExists('student_service_order_task_assignees');
    }
};
