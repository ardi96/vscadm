<?php

namespace App\Filament\Resources\MarketingSourceResource\Pages;

use App\Filament\Resources\MarketingSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingSources extends ListRecords
{
    protected static string $resource = MarketingSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
