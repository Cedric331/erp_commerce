<?php

namespace App\Filament\Resources\Tenancy;

use App\Models\Shop;
use App\Models\Permission;
use App\Models\Role;
use App\Models\StockStatus;
use App\Models\Storage;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopRegister extends RegisterTenant
{
    protected static ?string $model = Shop::class;

    protected static ?string $label = 'Ajouter un commerce';
    protected static ?string $pluralModelLabel = 'Commerces';
    protected static ?string $slug = 'create-commerce';

    public static function canView(): bool
    {
        return Auth::user()->isAdministrateurOrGerant() || !Auth::user()->hasTenant();
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
        $slug = $slug . '-' .Shop::where('slug', 'like', $slug . '-%')->count();
        $data['slug'] = $slug;

        return $data;
    }

    protected function afterRegister(): void
    {
        $this->tenant->users()->attach(Auth::user()->id);
        $tenantId = $this->tenant->id;

        DB::transaction(function () {
            $this->createDefaultStorage();
            $this->createDefaultStockStatuses();
        });

//        $rolesWithPermissions = [
//            Role::ROLE_GERANT => config('setting-permission.gerant'),
//        ];

//        foreach ($rolesWithPermissions as $roleName => $permissions) {
//            $role = Role::firstOrCreate([
//                'name' => $roleName
//            ]);
//
//            if ($permissions) {
//                $role->syncPermissions($permissions);
//            }
//        }
        $role = Role::where('name', Role::ROLE_GERANT)->first();
        setPermissionsTeamId($tenantId);
        Auth::user()->assignRole($role);
    }

    private function createDefaultStorage(): void
    {
        Storage::create([
            'name' => 'Magasin',
            'status' => Storage::STATUS_ACTIVE,
            'shop_id' => $this->tenant->id,
        ]);
    }

    private function createDefaultStockStatuses(): void
    {
        $statuses = [
            [
                'name' => StockStatus::STATUS_VENTE,
                'color' => StockStatus::COLOR_VERTE,
                'type' => StockStatus::TYPE_SORTIE,
            ],
            [
                'name' => StockStatus::STATUS_LIVRAISON,
                'color' => StockStatus::COLOR_ORANGE,
                'type' => StockStatus::TYPE_ENTREE,
            ],
            [
                'name' => StockStatus::STATUS_PERTE,
                'color' => StockStatus::COLOR_ROUGE,
                'type' => StockStatus::TYPE_SORTIE,
            ],
        ];

        foreach ($statuses as $status) {
            StockStatus::create(array_merge($status, ['shop_id' => $this->tenant->id]));
        }
    }

}
