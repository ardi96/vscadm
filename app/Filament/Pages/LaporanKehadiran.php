<?php

namespace App\Filament\Pages;

use App\Models\Member;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Table\Concerns\HasQuery;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Attributes\Url;


class LaporanKehadiran extends Page implements HasTable
{

    use InteractsWithTable, HasQuery;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.laporan-kehadiran';

    protected static ?string $navigationGroup = 'Laporan';


    
    #[Url]
    public ?array $tableFilters = null;


    public static function shouldRegisterNavigation() : bool
    {
        return Auth::user()->can('view absensi');
    }

    protected function getTableQuery(): Collection
    {
        // Fetch data using a custom query
        $data = DB::select('SELECT * from ABSENSIS');

        // Convert the result to a Laravel Collection for compatibility
        return collect($data);
    }

    private function getQuery() : Builder 
    {
        return Member::query();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query( $this->getQuery() )
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                TextColumn::make('kelas.name')->searchable(),
                TextColumn::make('kelas.coach.name')->searchable(),
                TextColumn::make('id')->formatStateUsing(function($record) {
                    return $record->getSessionCount(
                        $this->tableFilters['created_from']['from_date'],
                        $this->tableFilters['created_to']['to_date']
                    );
                })->label('Total Sesi')->alignCenter(),
                TextColumn::make('grade_id')->formatStateUsing(function($record) {
                    return $record->getAttendanceCount(
                        $this->tableFilters['created_from']['from_date'],
                        $this->tableFilters['created_to']['to_date']
                    );
                })->label('Total Kehadiran')->alignCenter()->action(
                    Action::make('view_kehadiran')->modal()->modalContent(function($record) 
                    { 
                        $absensi = $record->absensi->whereBetween('tanggal',[$this->tableFilters['created_from']['from_date'], 
                                                                                   $this->tableFilters['created_to']['to_date']]);

                        return view('filament.pages.view-absensi',['record' => $record,'absensi' => $absensi]);
                    })->modalSubmitActionLabel('Close')
                )
            ])
            ->filters([
                Filter::make('created_from')->form([
                    DatePicker::make('from_date')->label('Dari Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query;
                        // ->when(
                        //     $data['from_date'],
                        //     fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        // );
                }),
                Filter::make('created_to')->form([
                    DatePicker::make('to_date')->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query;
                        // ->when(
                        //     $data['to_date'],
                        //     fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        // );
                })
            ],layout: FiltersLayout::AboveContent)
            ->emptyStateHeading('Data tidak tersedia')
            ->headerActions([
                // Action::make('Download')->form([
                //     Placeholder::make('Info')->content('Masih Dalam Pengembangan')
                // ])
            ]);
    }
}
