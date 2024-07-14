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
}
