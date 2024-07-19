<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use Filament\Actions;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\MemberResource;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['parent_id'] = Auth()->user()->id;

        return $data; 
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        // if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
        //     return $resource::getUrl('view', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        // }

        // if ($resource::hasPage('edit') && $resource::canEdit($this->getRecord())) {
        //     return $resource::getUrl('edit', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        // }

        return $resource::getUrl('index');
    }

    public function afterCreate() : void 
    {
        $record = $this->record;

        if ( $record->payment_file_name != null && $record->payment_amount != null )
        {
            Payment::create([
                'amount' => $record->payment_amount,
                'payment_date' => Date::now(),
                'notes' => 'Pendaftaran a.n. ' . $record->name ,
                'bank' => 'N/A',
                'file_name' => $record->payment_file_name,
                'status' => 'pending'
            ]);
        }
    }
    
}
