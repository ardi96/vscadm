<?php

namespace App\Filament\Coach\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Absensi;
use Filament\Forms\Form;
use App\Models\SesiKelas;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Coach\Resources\AbsensiResource\Pages;
use App\Filament\Coach\Resources\AbsensiResource\RelationManagers;
use Carbon\Carbon;
use Filament\Tables\Enums\FiltersLayout;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 40;

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
                TextColumn::make('sesi_kelas.tanggal')->date('d-M-Y')->badge()->searchable(),
                TextColumn::make('member.name')->searchable(),
                TextColumn::make('member.kelas.name'),
                TextColumn::make('sesi_kelas.coach')->label('Coach')->searchable(),
                ToggleColumn::make('hadir')->label('Hadir')
            ])
            ->filters([
                Filter::make('kelas')
                ->form([
                    DatePicker::make('tanggal')->default(Carbon::now())->label('Kelas'),
                    Select::make('kelas_id')->options(Kelas::pluck('name','id'))->label('Kelas')
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                                            $data['tanggal'], 
                                            fn(Builder $query, $date) : Builder => $query->whereIn('sesi_kelas_id', SesiKelas::where('tanggal', $date)->get()->pluck('id'))
                                        )
                                 ->when(
                                            $data['kelas_id'],
                                            fn(Builder $query, $kelas_id) : Builder => $query->whereIn('sesi_kelas_id', SesiKelas::where('kelas_id',$kelas_id)->get()->pluck('id'))
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
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('hadir')->label('Hadir')
                    ->action(
                        function(Collection $selectedRecords) {
                            $selectedRecords->each( fn(Model $selectedRecord) => $selectedRecord->update(['hadir' => true]));
                        }
                    )
                    ->icon('heroicon-m-check-circle')
                    ->color('primary'),
                ]),
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
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
