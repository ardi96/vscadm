<?php

namespace App\Filament\Resources\ResignationResource\Pages;

use Filament\Actions;
use App\Models\Member;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ResignationResource;

class CreateResignation extends CreateRecord
{
    protected static string $resource = ResignationResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $member = Member::find( $data['member_id'] );
        $data['parent_id'] = $member->parent_id;
        $data['status'] = 0; // Set status to 'pending' on creation

        return $data;
    }
}
