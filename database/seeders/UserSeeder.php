<?php

namespace Database\Seeders;

use App\Models\Commercant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'email' => 'limacedric@hotmail.fr',
            'password' => Hash::make('password')
        ]);

        $userB = User::create([
            'name' => 'DEMO',
            'email' => 'demo@demo.com',
            'password' => Hash::make('password')
        ]);


        $role = Role::create([
            'name' => 'Administrateur',
            'guard_name' => 'web'
        ]);

        setPermissionsTeamId(Commercant::first()->id);

        $userA->assignRole($role);
        $userA->commercant()->attach(Commercant::first());

        $userB->assignRole($role);
        $userB->commercant()->attach(Commercant::first());
    }
}
