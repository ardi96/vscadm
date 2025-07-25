<?php

namespace App\Filament\Resources\ParentResource\Pages;

use App\Filament\Exports\UserExporter;
use App\Filament\Resources\ParentResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListParents extends ListRecords
{
    protected static string $resource = ParentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Export Data')
                ->exporter(UserExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
