<?php

namespace App\Filament\Portal\Resources\ResignationResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ResignationResource;

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

    public function beforeFill()
    {
        // Only allow editing if status is 'Pending'
        if ( $this->record->status != 0 ) {
            
            Notification::make()
                ->title('Error')
                ->body('Pengunduran diri tidak dapat diubah karena sudah diproses.')
                ->danger()
                ->send();

            $this->redirect( url(ResignationResource\Pages\ListResignations::getUrl()) );
        }   
    }
}
