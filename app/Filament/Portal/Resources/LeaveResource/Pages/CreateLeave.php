<?php

namespace App\Filament\Portal\Resources\LeaveResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use App\Services\LeaveService;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\LeaveResource;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;
    
    public function getTitle(): string
    {
        return 'Ajukan Cuti';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->id;
        $data['status'] = 0; // Set status to 'pending' on creation
        
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $leave = parent::handleRecordCreation($data);

        LeaveService::createLeaveInvoice( $leave , true);

        return $leave;
    }

    public function getRedirectUrl(): string
    {
        // $resource = static::getResource();

        $leave = $this->getRecord();

        $invoice = Invoice::where('type','leave')->where('member_id',$leave->member->id)->get()->last();
        
        return '/portal/checkout-page?id=' . $invoice->id; 

        // return $resource::getUrl('index');
    }
}
