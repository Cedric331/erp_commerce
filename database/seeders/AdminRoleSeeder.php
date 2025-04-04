<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un rôle administrateur global (sans tenant)
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrateur',
            'guard_name' => 'web',
            'shop_id' => null, // Rôle global sans tenant
        ]);

        // Vous pouvez également créer un utilisateur administrateur si nécessaire
        // ou assigner le rôle à un utilisateur existant
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
            ]
        );

        // Assigner le rôle global à l'utilisateur
        // Utiliser la relation morphToMany directement pour éviter les problèmes avec shop_id
        $admin->roles()->attach($adminRole->id, [
            'model_type' => get_class($admin),
        ]);
    }
}
