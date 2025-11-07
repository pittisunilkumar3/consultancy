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
        Schema::create('student_service_order_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_service_order_id');
            $table->unsignedBigInteger('service_id');
            $table->string('invoiceID')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->text('details')->nullable();
            $table->decimal('payable_amount', 12,2)->default(0);
            $table->decimal('total', 12,2)->default(0);
            $table->tinyInteger('payment_status')->default(0);
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
        Schema::dropIfExists('student_service_order_invoices');
    }
};
