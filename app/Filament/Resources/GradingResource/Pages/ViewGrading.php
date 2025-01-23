<?php

namespace App\Filament\Resources\GradingResource\Pages;

use App\Models\Grading;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\GradingResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Tables\Columns\TextColumn;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class ViewGrading extends ViewRecord
{
    protected static string $resource = GradingResource::class;


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

            //  EditAction::make('Edit')->icon('heroicon-m-pencil-square')->visible(fn( Grading $record) => $record->status != 'approved'),
            
            Action::make('Approve')->icon('heroicon-m-check-circle')->action(function(Grading $record) {
                
                $record->status = 'approved';
                $record->approved_at = now();
                $record->approved_by = Auth::user()->id;
                $record->save();

            })->requiresConfirmation()->visible(fn( Grading $record) => $record->status != 'approved' && Auth::user()->can('approve grading')),

            Html2MediaAction::make('Print')->icon('heroicon-m-printer')->color('primary')
                ->label('Print to PDF')->content( fn($record) => view('raport', ['record' => $record]) )
                ->margin([10,10,10,10])
                ->format('a4','mm'),

        ];
    }
}
