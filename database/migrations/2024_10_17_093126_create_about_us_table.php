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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('details')->nullable();
            $table->string('our_mission_title')->nullable();
            $table->longText('our_mission_details')->nullable();
            $table->string('our_mission_image')->nullable();
            $table->string('our_vision_title')->nullable();
            $table->longText('our_vision_details')->nullable();
            $table->string('our_vision_image')->nullable();
            $table->string('our_goal_title')->nullable();
            $table->longText('our_goal_details')->nullable();
            $table->string('our_goal_image')->nullable();
            $table->text('awards')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
