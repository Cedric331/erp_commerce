<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use App\Filament\Resources\ProduitResource\Widgets\ProfitabilityProduct;
use App\Models\Product;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;
use JaOcero\ActivityTimeline\Pages\ActivityTimelinePage;
use Spatie\Activitylog\Models\Activity;

class ViewProduitActivities extends ActivityTimelinePage
{
    protected static string $resource = ProduitResource::class;

    protected static string $view = 'filament.resources.produit-resource.pages.view-produit-activities';

    protected static ?string $title = 'Activités du produit';

    public $activities;

    protected function getHeaderWidgets(): array
    {
        return [
            ProfitabilityProduct::class
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getWidgetData(): array
    {
        return [
            'product' => $this->record
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        $this->activities = Activity::where('subject_id', $record)
            ->with('causer')
            ->where('subject_type', Product::class)
            ->orderBy('created_at', 'desc')
            ->get();

    }

}
