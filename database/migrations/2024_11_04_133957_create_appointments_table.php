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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_ID')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('consulter_id');
            $table->unsignedBigInteger('consultation_slot_id');
            $table->date('date');
            $table->tinyInteger('consultation_type');
            $table->string('consultation_link')->nullable();
            $table->unsignedBigInteger('meeting_provider_id')->nullable();
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->tinyInteger('payment_status')->default(PAYMENT_STATUS_NOT_INIT);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
