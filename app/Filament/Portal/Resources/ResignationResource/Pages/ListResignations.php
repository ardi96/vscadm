<?php

namespace App\Filament\Portal\Resources\ResignationResource\Pages;

use App\Filament\Portal\Resources\ResignationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResignations extends ListRecords
{
    protected static string $resource = ResignationResource::class;

    public function getTitle(): string
    {
        return 'Daftar Pengunduran Diri';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Ajukan Pengunduran Diri'),
        ];
    }
}
