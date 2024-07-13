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

    // protected function getFormActions(): array
    // {
    //     return [
    //         $this->getSaveFormAction(),
    //         $this->getCancelFormAction(),
    //         Actions\Action::make('Generate Invoice')->color(Color::Amber)
    //     ];
    // }
}
