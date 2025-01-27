<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TempUser extends Model
{
    protected $table = 'users';
}

class LaporanKehadiranCoach extends Page implements Tables\Contracts\HasTable
{

    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Laporan';

    protected static string $view = 'filament.pages.laporan-kehadiran-coach';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Use Query Builder to fetch data
        $data = DB::table('users')
            ->select('id', 'name', 'email', 'created_at')
            // ->where('is_admin','=', true)
            ->orderBy('created_at', 'desc')
            ->toSql();

        // Convert results to a Collection for compatibility
        // return collect($data);
        return TempUser::fromQuery($data);
    }


    // protected function getTableRecords(): iterable
    // {
    //     return DB::table('users')
    //         ->select('id', 'name', 'email', 'created_at')
    //         ->where('status', 'active')
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    // }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('ID'),
            Tables\Columns\TextColumn::make('name')->label('Name'),
            Tables\Columns\TextColumn::make('email')->label('Email'),
            Tables\Columns\TextColumn::make('created_at')->label('Created At')->date(),
        ];
    }

    /**
     * Disable pagination if using a raw query.
     */
    protected function isTablePaginationEnabled(): bool
    {
        return false; // Pagination requires Eloquent
    }
}
