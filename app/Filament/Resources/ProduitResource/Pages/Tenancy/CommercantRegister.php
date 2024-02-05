<?php

namespace App\Filament\Resources\ProduitResource\Pages\Tenancy;

use App\Models\Commercant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class CommercantRegister extends RegisterTenant
{
    protected static ?string $model = Commercant::class;

    protected static ?string $label = 'Ajouter un commerce';
    protected static ?string $pluralModelLabel = 'Commerces';
    protected static ?string $slug = 'add-commerce';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('enseigne')
                    ->label('Nom de l\'enseigne')
                    ->required()
                    ->unique(Commercant::class, 'enseigne', null, true,modifyRuleUsing: function (Unique $rule) {
                        return $rule->where('user_id', Auth::id());
                    })
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

    protected function handleRegistration(array $data): Commercant
    {
        $data['user_id'] = auth()->id();
        $data['enseigne'] = ucwords(strtolower($data['enseigne']));

        $slug = Str::slug($data['enseigne'], '-');
        if (Commercant::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Commercant::where('slug', 'like', $slug . '-%')->count();
        }
        $data['slug'] = $slug;

        return Commercant::create($data);
    }


}
