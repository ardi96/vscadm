<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\MemberResource;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Registrasi Baru'),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return "Daftar Registrasi";
    }
}
