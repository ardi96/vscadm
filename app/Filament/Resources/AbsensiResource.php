<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Member;
use App\Models\Absensi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\AbsensiResource\Pages;
use Filament\Tables\Filters\SelectFilter;

class AbsensiResource extends Resource
{
    protected static ?string $model = Member::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Coach';

    protected static ?int $navigationSort = 10;

    protected static ?string $pluralModelLabel = 'Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('sesi_kelas.tanggal')->date('d-M-Y')->badge()->searchable(),
                TextColumn::make('id')
                    ->label('Member ID')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => 'VSC' . str_pad($record->id, 4, '0', STR_PAD_LEFT)),
                TextColumn::make('name')->searchable(),
                TextColumn::make('costume_label')->searchable()->label('Panggilan'),
                TextColumn::make('parent_name')->searchable()->label('Orang Tua'),
                TextColumn::make('kelas.name'),
                TextColumn::make('grade.name'),
                // TextColumn::make('sesi_kelas.coach')->label('Coach')->searchable(),
                // ToggleColumn::make('hadir')->label('Hadir')
            ])
            ->filters([
                
                Filter::make('schedule')->form([
                    DatePicker::make('tanggal')->default(Carbon::now())->label('Tanggal'),
                    Select::make('waktu')->options([
                        'pagi' => 'Pagi',
                        'sore' => 'Sore',
                    ])->default('pagi')->label('Waktu'),
                    ]),

                Filter::make('grade')
                ->form([
                    Select::make('grade_id')->options(Grade::pluck('name','id'))->label('Grade')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['grade_id'],
                            fn(Builder $query, $grade_id) : Builder => $query->where('grade_id', $grade_id)
                        );
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('hadir')->label('Cek Hadir')
                    ->action(
                        function(Collection $selectedRecords, $livewire ) {
                            
                            $tanggal = $livewire->getTableFilterState('schedule')['tanggal'];
                            $waktu = $livewire->getTableFilterState('schedule')['waktu'];

                            foreach( $selectedRecords as $selectedRecord)
                            {
                                $absensi = Absensi::firstOrCreate([
                                    'member_id' => $selectedRecord->id,
                                    'grade_id' => $selectedRecord->grade_id,
                                    'tanggal' => $tanggal,
                                    'waktu'=> $waktu,
                                    'hadir' => true,
                                ]);
                                
                                $absensi->user_id = Auth::user()->id;
                                $absensi->save(); 
                            }
                        }
                    )
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->icon('heroicon-m-check-circle')
                    ->color('primary'),
                ])->label('Pilih Aksi'),
            ]);
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
            'index' => Pages\ListAbsensis::route('/'),
            // 'create' => Pages\CreateAbsensi::route('/create'),
            // 'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
