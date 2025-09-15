<?php

namespace App\Filament\Portal\Resources\ResignationResource\Pages;

use Filament\Actions;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\ResignationResource;
use App\Filament\Portal\Resources\InvoiceResource\Pages\ListInvoices;

class CreateResignation extends CreateRecord
{
    protected static string $resource = ResignationResource::class;


    protected static bool $canCreateAnother = false;

    public function getTitle(): string
    {
        return 'Ajukan Pengunduran Diri';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['parent_id'] = auth()->user()->id;
        $data['resignation_date'] = now()->endOfMonth()->addDay();
        $data['status'] = 0;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }

    

}
