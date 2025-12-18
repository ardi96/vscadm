<?php

namespace App\Filament\Resources\BeasiswaResource\Pages;

use App\Filament\Resources\BeasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateBeasiswa extends CreateRecord
{
    protected static string $resource = BeasiswaResource::class;

    public function getTitle(): string|Htmlable
    {
        return "Beasiswa Baru";
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data['status'] = 1;
        $data['created_by'] = auth()->user()->id;
        $data['approved_by'] = auth()->user()->id;
        return $data;   

     }


     protected function getRedirectUrl(): string
     {
        $resource = static::getResource();

        return $resource::getUrl('index');
     }


}
