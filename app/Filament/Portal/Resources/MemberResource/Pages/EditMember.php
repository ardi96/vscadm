<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use App\Filament\Portal\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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

        // if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
        //     return $resource::getUrl('view', ['record' => $this->getRecord()]);
        // }

        return $resource::getUrl('index');
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        // $payment_date = $data['payment_date'];
        // $bank = $data['bank'];
        // $notes = $data['notes'];
        
        // unset($data['bank']);
        // unset($data['payment_date']);
        // unset($data['notes']);

        //insert the member information
        $record->update($data);

        return $record; 
    }

}
