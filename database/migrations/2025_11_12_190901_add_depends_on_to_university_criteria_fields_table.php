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
        Schema::table('university_criteria_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('depends_on_criteria_field_id')->nullable()->after('options')->comment('ID of criteria field this depends on (for conditional fields)');
            $table->string('depends_on_value')->nullable()->after('depends_on_criteria_field_id')->comment('Value that parent field must have for this field to be required (e.g., "1" for boolean true)');
            $table->boolean('is_structured')->default(false)->after('depends_on_value')->comment('If true, this JSON field supports structured data (e.g., English tests with scores)');
            
            $table->foreign('depends_on_criteria_field_id')->references('id')->on('university_criteria_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('university_criteria_fields', function (Blueprint $table) {
            $table->dropForeign(['depends_on_criteria_field_id']);
            $table->dropColumn(['depends_on_criteria_field_id', 'depends_on_value', 'is_structured']);
        });
    }
};
