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
        Schema::create('student_service_order_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_service_order_id'); // Foreign key to Student Service Order
            $table->string('taskID')->nullable();
            $table->string('task_name');
            $table->longText('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('progress')->default(0);
            $table->tinyInteger('priority')->default(5);
            $table->tinyInteger('student_access')->default(0);
            $table->unsignedBigInteger('created_by')->nullable(); // Creator user ID
            $table->unsignedBigInteger('last_reply_id')->nullable(); // Last reply ID
            $table->unsignedBigInteger('last_reply_by')->nullable(); // Last reply user ID
            $table->timestamp('last_reply_time')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('student_service_order_tasks');
    }
};
