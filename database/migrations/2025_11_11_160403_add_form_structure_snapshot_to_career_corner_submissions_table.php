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
        Schema::table('career_corner_submissions', function (Blueprint $table) {
            $table->json('form_structure_snapshot')->nullable()->after('form_data')->comment('Snapshot of form structure and questions at submission time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_corner_submissions', function (Blueprint $table) {
            $table->dropColumn('form_structure_snapshot');
        });
    }
};
