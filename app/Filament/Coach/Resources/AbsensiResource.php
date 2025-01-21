<?php

namespace App\Filament\Coach\Resources;

use Forms\Get;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Kelas;
use App\Models\Member;
use App\Models\Absensi;
use Filament\Forms\Form;
use App\Models\SesiKelas;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Coach\Resources\AbsensiResource\Pages;
use App\Filament\Coach\Resources\AbsensiResource\RelationManagers;

class AbsensiResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 40;

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
                TextColumn::make('name')->searchable(),
                TextColumn::make('kelas.name'),
                TextColumn::make('grade.name'),
                // TextColumn::make('sesi_kelas.coach')->label('Coach')->searchable(),
                // ToggleColumn::make('hadir')->label('Hadir')
            ])
            ->filters([
                Filter::make('kelas')
                ->form([
                    DatePicker::make('tanggal')->default(Carbon::now())->label('Tanggal'),
                    Select::make('grade_id')->options(Grade::pluck('name','id'))->label('Grade')
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                                            $data['grade_id'],
                                            fn(Builder $query, $grade_id) : Builder => $query->where('grade_id', $grade_id)
                                 );
                })
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('hadir')->label('Cek Hadir')
                    ->action(
                        function(Collection $selectedRecords, $livewire ) {
                            
                            $tanggal = $livewire->getTableFilterState('kelas')['tanggal'];

                            foreach( $selectedRecords as $selectedRecord)
                            {
                                Absensi::firstOrCreate([
                                    'member_id' => $selectedRecord->id,
                                    'grade_id' => $selectedRecord->grade_id,
                                    'tanggal' => $tanggal,
                                    'hadir' => true,
                                    'user_id' => Auth::user()->id 
                                ]);
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
