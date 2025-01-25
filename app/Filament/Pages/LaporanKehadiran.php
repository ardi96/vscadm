<?php

namespace App\Filament\Pages;

use App\Models\Absensi;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Concerns\InteractsWithTable;

class LaporanKehadiran extends Page implements HasTable
{

    use InteractsWithTable;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-kehadiran';

    protected static ?string $navigationGroup = 'Laporan';

    public static function shouldRegisterNavigation() : bool
    {
        return Auth::user()->can('view absensi');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query( Absensi::query() )
            ->columns([
                TextColumn::make('member.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                TextColumn::make('member.kelas.name')->searchable(),
                TextColumn::make('member.kelas.coach.name')->searchable(),
                TextColumn::make('tanggal')->date('d-M-Y'),
                CheckboxColumn::make('hadir')->disabled()->summarize(
                    Count::make()->label('Total')
                )->alignCenter()
            ])
            ->filters([
                Filter::make('created_at')->form([
                    DatePicker::make('from_date')->label('Dari Tanggal'),
                    DatePicker::make('to_date')->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['to_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
            ],layout: FiltersLayout::AboveContent)
            ->emptyStateHeading('Data tidak tersedia')
            ->headerActions([
                Action::make('Download')->form([
                    Placeholder::make('Info')->content('Masih Dalam Pengembangan')
                ])
            ]);
    }
}
