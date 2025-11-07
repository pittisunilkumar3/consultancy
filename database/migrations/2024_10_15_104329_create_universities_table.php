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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('logo')->nullable();
            $table->string('country_id')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->integer('world_ranking')->nullable();
            $table->integer('international_student')->nullable();
            $table->longText('details')->nullable();
            $table->text('core_benefits_title')->nullable();
            $table->string('core_benefits_icon')->nullable();
            $table->string('gallery_image')->nullable();
            $table->integer('avg_cost');
            $table->tinyInteger('feature')->default(STATUS_PENDING);
            $table->tinyInteger('top_university')->default(STATUS_PENDING);
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('universities');
    }
};
