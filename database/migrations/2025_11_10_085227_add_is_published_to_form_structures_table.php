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
        Schema::table('form_structures', function (Blueprint $table) {
            if (!Schema::hasColumn('form_structures', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('meta');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_structures', function (Blueprint $table) {
            if (Schema::hasColumn('form_structures', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};
