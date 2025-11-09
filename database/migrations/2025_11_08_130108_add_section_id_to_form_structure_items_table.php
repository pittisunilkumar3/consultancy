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
        Schema::table('form_structure_items', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('form_structure_items', 'section_id')) {
                $table->foreignId('section_id')->nullable()->after('structure_id')
                    ->constrained('form_structure_sections')->onDelete('cascade');
            }
            
            // Add index for faster queries (with shorter name) if it doesn't exist
            $indexName = 'fsi_structure_section_parent_order_idx';
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('form_structure_items');
            if (!isset($indexesFound[$indexName])) {
                $table->index(['structure_id', 'section_id', 'parent_item_id', 'order'], $indexName);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_structure_items', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropIndex('fsi_structure_section_parent_order_idx');
            $table->dropColumn('section_id');
        });
    }
};
