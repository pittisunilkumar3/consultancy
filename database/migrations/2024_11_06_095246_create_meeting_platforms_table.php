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
        Schema::create('meeting_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('image', 255)->nullable();
            $table->tinyInteger('type');
            $table->string('account_id', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('key', 255)->nullable();
            $table->string('secret', 255)->nullable();
            $table->mediumText('token')->nullable();
            $table->string('calender_id', 255)->nullable();
            $table->string('timezone', 255)->nullable();
            $table->mediumText('address')->nullable();
            $table->tinyInteger('host_video')->default(3);
            $table->tinyInteger('participant_video')->default(3);
            $table->tinyInteger('waiting_room')->default(3);
            $table->tinyInteger('status')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_platforms');
    }
};

