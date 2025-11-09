<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormStructureItemsTable extends Migration
{
    public function up()
    {
        Schema::create('form_structure_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->constrained('form_structures')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('parent_item_id')->nullable()->constrained('form_structure_items')->onDelete('cascade');
            $table->string('parent_option_value')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Index for faster queries on structure and parent lookups
            $table->index(['structure_id', 'parent_item_id', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_structure_items');
    }
}