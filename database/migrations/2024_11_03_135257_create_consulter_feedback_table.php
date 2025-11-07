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
        Schema::create('consulter_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consulter_id');
            $table->unsignedBigInteger('student_id');
            $table->text('comment');
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
        Schema::dropIfExists('consulter_feedback');
    }
};
