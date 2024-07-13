<?php

namespace App\Filament\Resources\MarketingSourceResource\Pages;

use App\Filament\Resources\MarketingSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingSource extends EditRecord
{
    protected static string $resource = MarketingSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
