<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MemberResource;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
            return $resource::getUrl('view', ['record' => $this->getRecord()]);
        }

        return $resource::getUrl('index');
    }

    // protected function getFormActions(): array
    // {
    //     return [
    //         $this->getSaveFormAction(),
    //         $this->getCancelFormAction(),
    //         Actions\Action::make('Generate Invoice')->color(Color::Amber)
    //     ];
    // }
}
