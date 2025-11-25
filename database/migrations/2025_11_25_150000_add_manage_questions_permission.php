<?php

use Illuminate\Database\Migrations\Migration;
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
            ['name' => 'Manage Questions', 'guard_name' => 'web'],
            ['name' => 'Manage Questions', 'guard_name' => 'web']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the permission
        $permission = Permission::where('name', 'Manage Questions')
            ->where('guard_name', 'web')
            ->first();

        if ($permission) {
            $permission->delete();
        }
    }
};
