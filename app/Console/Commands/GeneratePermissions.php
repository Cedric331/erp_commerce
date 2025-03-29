<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePermissions extends Command
{
    protected $signature = 'generate:permissions';

    protected $description = 'Generate permissions from configuration';

    public function handle()
    {
        $permissions = config('setting-permission.permissions');

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->info('Permissions generated successfully.');
    }
}
