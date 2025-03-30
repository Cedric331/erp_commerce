<?php

namespace App\Filament\Traits;

use App\Models\Stock;
use Filament\Notifications\Notification;

trait HandlesCalendarActions
{
    public function deleteCalendarEvent(int $eventId): void
    {
        $stock = Stock::find($eventId);

        if (! $stock || ! $stock->scheduled_date?->isFuture()) {
            Notification::make()
                ->title('Action non autorisée')
                ->danger()
                ->send();

            return;
        }

        $stock->delete();

        Notification::make()
            ->title('Mouvement de stock supprimé')
            ->success()
            ->send();

        $this->dispatch('refresh-calendar');
    }

    public function moveCalendarEvent(int $eventId, string $newDate): void
    {
        $stock = Stock::find($eventId);

        if (! $stock || ! $stock->scheduled_date?->isFuture()) {
            Notification::make()
                ->title('Action non autorisée')
                ->danger()
                ->send();

            return;
        }

        $stock->update(['scheduled_date' => $newDate]);

        Notification::make()
            ->title('Date modifiée avec succès')
            ->success()
            ->send();

        $this->dispatch('refresh-calendar');
    }
}
