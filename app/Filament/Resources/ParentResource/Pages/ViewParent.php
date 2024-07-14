<?php

namespace App\Filament\Resources\ParentResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ParentResource;
use Filament\Infolists\Components\TextEntry;

class ViewParent extends ViewRecord
{
    protected static string $resource = ParentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')->label('Nama Lengkap'),
            TextEntry::make('email')->label('Email'),
            TextEntry::make('mobile_no')->label('No. WA Aktif'),
            TextEntry::make('created_at')->label('Tanggal Akun Terdaftar')->dateTime('d-M-Y H:i:s'),
        ])
        ->inlineLabel(false);
    }
}
