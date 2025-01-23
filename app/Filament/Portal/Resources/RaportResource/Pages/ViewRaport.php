<?php

namespace App\Filament\Portal\Resources\RaportResource\Pages;

use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Portal\Resources\RaportResource;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewRaport extends ViewRecord
{
    protected static string $resource = RaportResource::class;

    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('member.name'),            
            TextEntry::make('member.grade.name')->label('Grade'),            
            TextEntry::make('created_at')->label('Grading Date')->date('d-M-Y')->badge(),            
            TextEntry::make('marks')->label('Nilai'),            
            TextEntry::make('decision')->label('Keputusan')->formatStateUsing(fn($state) : string => 
                $state == 1 ? 'Naik Kelas' : 'Tidak Naik Kelas'
            ),          
            TableRepeatableEntry::make('gradingItems')->schema([
                TextEntry::make('aspect')->label('Aspek'),
                TextEntry::make('mark')->label('Nilai'),
            ])->columns(2)->columnSpanFull()->label('Penilaian')->striped(),  
            TextEntry::make('notes')->html()->columnSpanFull(),
            TextEntry::make('status')->label('Status'),
        ]);        
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print')->icon('heroicon-m-printer')->color('primary')
                ->url( fn($record) : string => config('app.url').'/download/raport/'. $record->id)
                ->openUrlInNewTab()
        ];
    }
}
