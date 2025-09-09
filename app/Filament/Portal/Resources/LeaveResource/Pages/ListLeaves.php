<?php

namespace App\Filament\Portal\Resources\LeaveResource\Pages;

use App\Filament\Portal\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaves extends ListRecords
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Ajukan Cuti'),
        ];
    }

    public function getTitle(): string
    {
        return 'Daftar Cuti';
    }
}
