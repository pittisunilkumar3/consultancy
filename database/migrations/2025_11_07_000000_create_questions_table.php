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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable()->comment('optional unique key for reference');
            $table->text('question')->nullable();
            $table->text('help_text')->nullable();
            $table->string('type')->default('text')->comment('input type: text, textarea, select, radio, checkbox, etc');
            $table->json('options')->nullable()->comment('json encoded options for select/radio/checkbox');
            $table->integer('order')->default(0);
            $table->boolean('required')->default(false);
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
        Schema::dropIfExists('questions');
    }
};
