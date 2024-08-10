<?php

namespace App\Filament\Pages;

use App\Models\Commercant;
use App\Notifications\Contact;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Support extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.support';

    protected static ?string $slug = 'support';

    protected ?string $heading = 'Contacter le support';
    protected ?string $subheading = 'Vous avez une question, une demande d\'amélioration ou un problème ? Nous sommes là pour vous aider.';

    public ?array $data = [
        'subject' => null,
        'message' => null,
    ];

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function sendMessage(): void
    {
        $this->validate([
            'data.subject' => 'required|string',
            'data.message' => 'required|string',
        ]);

        Auth::user()->notify(new Contact($this->data, Commercant::find(Filament::getTenant()->id), Auth::user()));

        Notification::make()
            ->title('Message envoyé avec succès')
            ->body('Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.')
            ->success()
            ->duration(10000)
            ->send();

        $this->data = [
            'subject' => null,
            'message' => null,
        ];
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('subject')
                    ->label('Sujet de la demande')
                    ->placeholder('Sélectionnez un sujet')
                    ->options([
                        'question' => 'Question',
                        'improvement' => 'Demande d\'amélioration',
                        'problem' => 'Problème',
                    ])
                    ->validationMessages([
                        'required' => 'Veuillez sélectionner un sujet',
                    ])
                    ->required(),
                RichEditor::make('message')
                    ->label('Message')
                    ->placeholder('Entrez votre message ici')
                    ->validationMessages([
                        'required' => 'Veuillez entrer un message',
                    ])
                    ->required(),
                ])
                ->statePath('data');
    }

}
