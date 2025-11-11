<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the permission if it doesn't exist
        Permission::firstOrCreate(
            ['name' => 'Manage Career Corner Submissions', 'guard_name' => 'web'],
            ['name' => 'Manage Career Corner Submissions', 'guard_name' => 'web']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the permission
        $permission = Permission::where('name', 'Manage Career Corner Submissions')
            ->where('guard_name', 'web')
            ->first();
        
        if ($permission) {
            $permission->delete();
        }
    }
};
