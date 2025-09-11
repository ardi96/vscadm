<?php

namespace App\Filament\Portal\Resources\ResignationResource\Pages;

use App\Filament\Portal\Resources\ResignationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResignation extends EditRecord
{
    protected static string $resource = ResignationResource::class;


    public function getTitle(): string
    {
        return 'Ubah Pengunduran Diri';
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
