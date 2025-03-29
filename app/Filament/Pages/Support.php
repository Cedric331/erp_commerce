<?php

namespace App\Filament\Pages;

use App\Models\Shop;
use App\Notifications\ContactSupport;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $this->validate([
                'data.subject' => 'required|string|max:255',
                'data.message' => 'required|string|min:10|max:5000',
            ]);

            $shop = Shop::findOrFail(Filament::getTenant()->id);

            $this->data['user_email'] = Auth::user()->email;
            $this->data['user_name'] = Auth::user()->name;
            $this->data['shop_email'] = $shop->email;
            $this->data['shop_enseigne'] = $shop->enseigne;

            Auth::user()->notify(new ContactSupport($this->data));

            Notification::make()
                ->title('Message envoyé avec succès')
                ->body('Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.')
                ->success()
                ->duration(10000)
                ->send();

            $this->reset('data');
        } catch (ValidationException $e) {
            Notification::make()
                ->title('Erreur de validation')
                ->body('Veuillez vérifier les champs du formulaire.')
                ->danger()
                ->duration(5000)
                ->send();
            throw $e;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erreur')
                ->body('Une erreur est survenue lors de l\'envoi du message.')
                ->danger()
                ->duration(5000)
                ->send();
            throw $e;
        }
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
                    ->disableToolbarButtons([
                        'link',
                    ])
                    ->placeholder('Entrez votre message ici')
                    ->validationMessages([
                        'required' => 'Veuillez entrer un message',
                    ])
                    ->required(),
            ])
            ->statePath('data');
    }
}
