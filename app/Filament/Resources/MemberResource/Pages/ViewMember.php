<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\MemberResource;
use Filament\Infolists\Components\TextEntry;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

        

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')->label('Nama Lengkap'),
            TextEntry::make('gender')->label('Jenis Kelamin'),
            TextEntry::make('school_name')->label('Nama Sekolah'),
            TextEntry::make('parent_name')->label('Nama Orang Tua'),
            TextEntry::make('parent_mobile_no')->label('Nama WA Orang Tua'),
            TextEntry::make('date_of_birth')->label('Tanggal Lahir')->date('d-M-Y'),
            TextEntry::make('costume_label')->label('Nama Tertera Baju'),
            TextEntry::make('costume_size')->label('Ukuran Baju'),
            TextEntry::make('marketingSource.name')->label('Channel Marketing'),
            TextEntry::make('marketing_source_other')->label('Channel Marketing Lainnya'),
            TextEntry::make('instagram')->label('Akun Instagram'),
            TextEntry::make('package.name')->label('Nama Kelas'),
            TextEntry::make('start_date')->label('Bergabung Mulai')->date('d-M-Y'),
            TextEntry::make('status')->label('Status Keanggotaan'),
        ])
        ->inlineLabel(false);
    }

}
