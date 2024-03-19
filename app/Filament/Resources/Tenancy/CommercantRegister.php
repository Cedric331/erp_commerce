<?php

namespace App\Filament\Resources\Tenancy;

use App\Models\Commercant;
use App\Models\Permission;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommercantRegister extends RegisterTenant
{
    protected static ?string $model = Commercant::class;

    protected static ?string $label = 'Ajouter un commerce';
    protected static ?string $pluralModelLabel = 'Commerces';
    protected static ?string $slug = 'create-commerce';

    public static function canView(): bool
    {
        return Auth::user()->isAdministrateurOrGerant();
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


    public static function getLabel(): string
    {
        return static::$label;
    }

    public function mutateFormDataBeforeRegister(array $data): array
    {
        $data['enseigne'] = ucwords(strtolower($data['enseigne']));

        $slug = Str::slug($data['enseigne'], '-');
        $slug = $slug . '-' .Commercant::where('slug', 'like', $slug . '-%')->count();
        $data['slug'] = $slug;

        return $data;
    }

    protected function afterRegister(): void
    {
        $this->tenant->users()->attach(Auth::user()->id);
        $tenantId = $this->tenant->id;

        $rolesWithPermissions = [
            Role::ROLE_GERANT => config('setting-permission.gerant'),
        ];

        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::create([
                'name' => $roleName,
                'commercant_id' => $tenantId,
            ]);

            if ($permissions) {
                $role->syncPermissions($permissions);
            }
        }
        $role = Role::where('name', Role::ROLE_GERANT)->first();
        setPermissionsTeamId($tenantId);
        Auth::user()->assignRole($role);
    }

}
