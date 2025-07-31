<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MemberResource;

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

        if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
            return $resource::getUrl('view', ['record' => $this->getRecord()]);
        }

        return $resource::getUrl('index');
    }

    /**
     * Mutate the form data before saving.
     * This method is used to set the grade_id based on the selected kelas_id.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $kelas_id = $data['kelas_id'] ?? null;

        if ( $kelas_id ) {
            $kelas = \App\Models\Kelas::find($kelas_id);
            if ( $kelas ) {
                $data['grade_id'] = $kelas->grade_id;
            }
        }

        return $data;
    }
}