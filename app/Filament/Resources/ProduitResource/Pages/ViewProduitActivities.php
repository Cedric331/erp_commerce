<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use App\Models\Produit;
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

    public function mount($record): void
    {
        parent::mount($record);

        $this->activities = Activity::where('subject_id', $record)
            ->with('causer')
            ->where('subject_type', Produit::class)
            ->orderBy('created_at', 'desc')
            ->get();

    }

}
