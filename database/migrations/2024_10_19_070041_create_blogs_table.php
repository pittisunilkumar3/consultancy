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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('title')->nullable();
            $table->string('og_image')->nullable();
            $table->longText('details')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('meta_keyword')->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->longText('meta_description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
