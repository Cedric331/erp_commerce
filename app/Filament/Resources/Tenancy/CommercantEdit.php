<?php

namespace App\Filament\Resources\Tenancy;

use App\Models\Commercant;
use App\Models\Permission;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommercantEdit extends EditTenantProfile
{
    protected static ?string $model = Commercant::class;

    protected static ?string $label = 'Modifier le commerce';
    protected static ?string $pluralModelLabel = 'Commerces';

    protected static ?string $slug = 'edit-commerce';

    public static function canView(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
       return Auth::user()->hasPermissionTo('Gestion commerce') || Auth::user()->isAdministrateurOrGerant();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('enseigne')
                    ->label('Nom de l\'enseigne')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(10)
                    ->numeric(),
                Forms\Components\TextInput::make('adresse')
                    ->label('Adresse')
                    ->maxLength(255),
                Forms\Components\TextInput::make('adresse_2')
                    ->label('Adresse 2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ville')
                    ->maxLength(255),
                Forms\Components\TextInput::make('code_postal')
                    ->maxLength(6),
                Forms\Components\Select::make('pays')
                    ->label('Pays')
                    ->options([
                        'France' => 'France',
                        'Belgique' => 'Belgique',
                        'Suisse' => 'Suisse',
                        'Luxembourg' => 'Luxembourg'
                    ])
                    ->required(),
            ]);
    }

    protected function getRedirectUrl(): ?string
    {
        return '/app';
    }


    public static function getLabel(): string
    {
        return static::$label;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $data['enseigne'] = ucwords(strtolower($data['enseigne']));

        $slug = Str::slug($data['enseigne'], '-');
        $slug = $slug . '-' .Commercant::where('slug', 'like', $slug . '-%')->count();
        $data['slug'] = $slug;


        return $data;
    }

//    protected function afterSave(): void
//    {
//        $this->tenant->users()->attach(Auth::user()->id);
//        $tenantId = Filament::getTenant()->id;
//
//        $permissions = Permission::ALL_PERMISSION;
//
//        foreach ($permissions as $permission) {
//            Permission::create([
//                'name' => $permission,
//                'guard_name' => 'web',
//                'commercant_id' => $tenantId
//            ]);
//        }
//
//        Role::create([
//            'name' => Role::ROLE_ADMIN,
//            'commercant_id' => $tenantId
//        ]);
//
//        $role = Role::create([
//            'name' => Role::ROLE_MANAGER,
//            'commercant_id' => $tenantId
//        ]);
//
//        $role->syncPermissions(Permission::PERMISSION_MANAGER);
//
//        $role = Role::create([
//            'name' => Role::ROLE_GERANT,
//            'commercant_id' => $tenantId
//        ]);
//
//        $role->syncPermissions(Permission::PERMISSION_GERANT);
//
//        $role = Role::create([
//            'name' => Role::ROLE_SERVEUR,
//            'commercant_id' => $tenantId
//        ]);
//        $role->syncPermissions(Permission::PERMISSION_SERVEUR);
//
//    }
}
