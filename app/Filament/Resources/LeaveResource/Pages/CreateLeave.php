<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->id;
        $data['status'] = 0; // Set status to 'pending' on creation

        return $data;
    }

    public function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }

    
}
