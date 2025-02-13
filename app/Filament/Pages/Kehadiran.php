<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Absensi;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class Kehadiran extends Page implements HasTable
{
    use InteractsWithTable;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absensi';

    protected static ?string $navigationGroup = 'Coach';

    protected static ?int $navigationSort = 20 ;

    protected static ?string $navigationLabel = 'View Absensi';

    public static function shouldRegisterNavigation() : bool
    {
        return Auth::user()->can('view absensi');   
    }

    public static function table(Table $table) : Table 
    {
        return $table
            ->query( Absensi::query() )
            ->columns([
                TextColumn::make('tanggal')->date('d-M-Y')->badge(),
                TextColumn::make('member.name'),
                TextColumn::make('grade.name'),
                CheckboxColumn::make('hadir')->disabled()
            ])
            ->filters([
                Filter::make('kehadiran')->form([
                    Select::make('grade_id')->options(Grade::pluck('name','id'))->label('Grade'),
                    DatePicker::make('tanggal')->default( Carbon::now() )->format('d-M-Y'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                                    $data['grade_id'],
                                    fn(Builder $query, $grade_id) : Builder => $query->where('grade_id', $grade_id)
                                 )
                                 ->when(
                                    $data['tanggal'],
                                    fn(Builder $query, $tanggal) : Builder => $query->where('tanggal', $tanggal)
                                 );
                })
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->emptyStateHeading('Tidak ada kehadiran')
            ->actions([
                Action::make('delete')->icon('heroicon-o-x-circle')->label('Hapus')
                    ->requiresConfirmation()->color('danger')->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalHeading('Hapus Kehadiran')
                    ->action(fn(Absensi $absensi) => $absensi->delete())
                    ->visible(fn() => Auth::user()->can('delete absensi')),
            ])
            ;
    }

}
