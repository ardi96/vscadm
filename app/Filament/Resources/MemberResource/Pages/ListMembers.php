<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Exports\MemberExporter;
use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Export Data')
                ->exporter(MemberExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
