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
        Schema::create('student_service_order_task_conversation_seens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_task_id')->default(0); // Reference to Student Service Order Task
            $table->unsignedBigInteger('order_task_conversation_id')->default(0); // Reference to Task Conversation
            $table->tinyInteger('is_seen')->default(1); // Seen status
            $table->unsignedBigInteger('created_by')->default(0); // User who marked it as seen
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
        Schema::dropIfExists('student_service_order_task_conversation_seens');
    }
};
