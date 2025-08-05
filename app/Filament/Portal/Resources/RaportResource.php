<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\RaportResource\Pages;
use App\Filament\Portal\Resources\RaportResource\RelationManagers;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class RaportResource extends Resource
{
    protected static ?string $model = Grading::class;

    protected static ?string $navigationLabel = 'Raport';

    protected static ?string $modelLabel= 'Raport';
    
    protected static ?string $slug = 'raport';

    protected static ?string $pluralModelLabel= 'Raport';

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?int $navigationSort = 90;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')->label('Nama'),
                TextColumn::make('grade.name')->label('Grade'),
                TextColumn::make('year')->label('Periode')->formatStateUsing(
                    fn($record) => date("F", strtotime(date("Y") ."-". $record->month ."-01"))  .' '. $record->year    
                ),
                TextColumn::make('marks')->label('Nilai'),
                CheckboxColumn::make('decision')->label('Naik Tingkat')->disabled()->alignCenter(),
                // TextColumn::Make('created_at')->label('Tanggal Penilaian')->date('d-M-Y')->badge()
            ])
            ->filters([
                SelectFilter::make('member_id')
                    ->label('Nama Anak')
                    ->options(Member::where('parent_id', Auth::user()->id)->pluck('name','id'))
            ])
            ->actions([
                Action::make('Download')
                    ->before(function (Action $action, $record) {
                        $member = Member::find($record->member_id);

                        $isNotLunas = ( $member->balance > 0 ) ;
                        
                        $isNotLengkap = ( $member->kelas_id == null || 
                                  $member->name == null ||
                                  $member->marketing_source_id == null ||
                                  $member->parent_id == null ||
                                  $member->class_package_id == null ||
                                  $member->costume_label == null ); 

                        if ( $isNotLunas && $isNotLengkap ) {
                            Notification::make()
                                ->title('Anda masih memiliki tunggakan dan data anak anda belum lengkap')
                                ->body('Silakan lunasi pembayaran sebelum melihat raport dan lengkapi data anak anda.')
                                ->danger()
                                ->actions([
                                    NotificationAction::make('Lihat Tagihan')
                                        ->color('primary')
                                        ->icon('heroicon-m-document-text')
                                        ->url(route('filament.portal.resources.invoices.index')),
                                    NotificationAction::make('Lengkapi Data')
                                        ->color('primary')
                                        ->icon('heroicon-m-calendar')
                                        ->url(route('filament.portal.resources.members.edit', ['record' => $member->id]))
                                    ])
                                ->send();

                            $action->cancel();
                        }
                        else if ( $isNotLunas ) {

                            Notification::make()
                                ->title('Anda masih memiliki tunggakan')
                                ->body('Silakan lunasi pembayaran sebelum melihat raport.')
                                ->danger()
                                ->actions([
                                    NotificationAction::make('Lihat Tagihan')
                                        ->color('primary')
                                        ->icon('heroicon-m-document-text')
                                        ->url(route('filament.portal.resources.invoices.index'))
                                    ])
                                ->send();

                            $action->cancel();
                        }
                        else if ( $isNotLengkap ) {
                            Notification::make()
                                ->title('Data anak anda belum lengkap')
                                ->body('Silakan lengkapi data anak anda.')
                                ->danger()
                                ->actions([
                                    NotificationAction::make('Lengkapi Data')
                                        ->color('primary')
                                        ->icon('heroicon-m-calendar')
                                        ->url(route('filament.portal.resources.members.edit', ['record' => $member->id]))
                                    ])
                                ->send();

                            $action->cancel();
                        }
                    })
                    ->icon('heroicon-m-arrow-down-circle')
                    ->action( function ($record) {
                        return response()->download(storage_path('app/public/' . $record->raport_file));
                    })
                    // ->action(fn($record) => redirect()->route('filament.portal.resources.raport.view', ['record' => $record->id]))
                    // ->url(fn($record) => static::getUrl('view', ['record' => $record]))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])->modifyQueryUsing(fn(Builder $query) => $query->whereIn('member_id', Member::where('parent_id', Auth::user()->id )->pluck('id')) )
            ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaports::route('/'),
            'view' => Pages\ViewRaport::route('/{record}')
        ];
    }
}
