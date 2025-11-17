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
            $table->json('options')->nullable()->after('description')->comment('Predefined options for JSON/array type fields (e.g., ["UG", "PG"] for degree types)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('university_criteria_fields', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
