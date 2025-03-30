<?php

namespace App\Filament\Widgets;

use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Saade\FilamentFullCalendar\Data\Event;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Data\EventData;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Stock::class;

    public string|int|null|\Illuminate\Database\Eloquent\Model $record = null;

    public function onEventClick(array $event): void
    {
        return;
    }

    protected function headerActions(): array
    {
        return [];
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, el }) {
            const tooltip = `
                Produit : \${event.extendedProps.product_name}<br>
                Statut : \${event.extendedProps.status}<br>
                Quantité : \${event.extendedProps.quantity}
            `;

            el.setAttribute("data-tooltip", tooltip);
            el.setAttribute("data-tooltip-style", "light"); // optionnel si tu utilises Tippy ou autre
            el.setAttribute("title", tooltip); // fallback navigateur
        }
    JS;
    }


    public function config(): array
    {
        return [
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay,dayGridMonth',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
            'firstDay' => 1,
            'locale' => 'fr',
            'timeZone' => 'Europe/Paris',
            'editable' => false,
            'selectable' => false,
            'eventClick' => false,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'config' => [
                'headerToolbar' => [
                    'left' => 'dayGridMonth,dayGridWeek,dayGridDay',
                    'center' => 'title',
                    'right' => 'prev,next today',
                ],
                'firstDay' => 1,
                'locale' => 'fr',
                'height' => '700px',
                'selectable' => false,
                'editable' => false,
                'timeZone' => 'Europe/Paris',
            ],
        ];
    }

    public function fetchEvents(array $info): array
    {
        return Stock::query()
            ->where('shop_id', Filament::getTenant()->id)
            ->where(function ($query) use ($info) {
                $query->whereBetween('scheduled_date', [$info['start'], $info['end']])
                    ->orWhereBetween('created_at', [$info['start'], $info['end']]);
            })
            ->with(['product', 'stockStatus'])
            ->get()
            ->map(function (Stock $stock) {
                $date = $this->resolveDate($stock);
                $color = $stock->stockStatus->type === StockStatus::TYPE_ENTREE ? '#10B981' : '#EF4444';

                return EventData::make()
                    ->id($stock->id)
                    ->title("{$stock->product->name} - {$stock->quantity} unités")
                    ->start($date)
                    ->end($date)
                    ->allDay(true)
                    ->backgroundColor($color)
                    ->borderColor($color)
                    ->extendedProps([
                        'product_name' => $stock->product->name,
                        'status' => $stock->stockStatus->name,
                        'quantity' => $stock->quantity,
                        'note' => $stock->note,
                        'type' => $stock->stockStatus->type,
                    ]);
            })
            ->toArray();
    }

    protected function resolveDate(Stock $stock): \Illuminate\Support\Carbon
    {
        return $stock->scheduled_date ?? $stock->created_at;
    }
}
