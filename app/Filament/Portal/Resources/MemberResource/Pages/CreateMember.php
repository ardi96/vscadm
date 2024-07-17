<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use App\Filament\Portal\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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
}
