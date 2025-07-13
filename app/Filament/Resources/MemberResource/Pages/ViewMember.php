<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MemberAccepted;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\MemberResource;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Infolists\Components\TextEntry;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit Anggota'),
            Actions\Action::make('activate')->label('Aktifkan Keanggotaan')
                ->visible(fn() => $this->getRecord()->status == 'pending')
                ->action(function() {
                    $this->getRecord()->status = 'active';
                    $this->getRecord()->save();

                    $user = User::find($this->getRecord()->parent_id);

                    $user->notify(new MemberAccepted( $this->getRecord() ));
                })
                ->after(fn() => $this->refreshFormData(['status'])),
        ];
    }

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
            TextEntry::make('package.name')->label('Paket'),
            // TextEntry::make('kelas.name')->label('Kelas'),
            TextEntry::make('grade.name')->label('Grade'),
            // TextEntry::make('start_date')->label('Tanggal Mulai')->date('d-M-Y'),
            TextEntry::make('status')->label('Status Keanggotaan')->badge()->color(Color::Amber),
            TextEntry::make('created_at')->label('Tanggal Registrasi')->date('d-M-Y'),
            TextEntry::make('balance')->label('Outstanding')->money('IDR'),
        ])
        ->inlineLabel(false);
    }

}
