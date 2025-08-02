<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use App\Models\Kelas;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\MemberResource;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $kelas = Kelas::find($data['kelas_id']);
        
        if ($kelas) {
            $data['grade_id'] = $kelas->grade_id;
        } else {
            $data['grade_id'] = null;
        }
        return $data;
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
