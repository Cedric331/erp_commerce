<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userA = User::create([
            'name' => 'Cedric',
            'email' => 'test@test.fr',
            'password' => Hash::make('password'),
        ]);

        $userB = User::create([
            'name' => 'DEMO',
            'email' => 'demo@demo.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::create([
            'name' => 'Administrateur',
            'guard_name' => 'web',
        ]);

        setPermissionsTeamId(Shop::first()->id);

        $userA->assignRole($role);
        $userA->shop()->attach(Shop::first());

        $userB->assignRole($role);
        $userB->shop()->attach(Shop::first());
    }
}
