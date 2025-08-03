<?php

namespace App\Filament\Resources\GradingResource\Pages;

use App\Models\Member;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\GradingResource;

class CreateGrading extends CreateRecord
{
    protected static string $resource = GradingResource::class;

    protected static ?string $title = 'Upload Raport';
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?string $navigationLabel = 'Upload Raport';


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $member = Member::find($data['member_id']);
        
        $data['grade_id'] = $member->grade_id;
        $data['approved_at'] = now();
        $data['approved_by'] = auth()->user()->id;                       
        $data['status'] = 'approved';

        return $data;   
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
