<?php

namespace Database\Seeders;

use App\Models\Commercant;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('setting-permission.permissions');

        $role = Role::create([
            'name' => 'Gérant',
            'guard_name' => 'web'
        ]);

        foreach ($permissions as $permission) {
           \Spatie\Permission\Models\Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $role->syncPermissions(Permission::all());

    }
}
