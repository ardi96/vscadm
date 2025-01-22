<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use App\Models\Absensi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\AbsensiResource\Pages;
use App\Filament\Portal\Resources\AbsensiResource\RelationManagers;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationLabel = 'Kehadiran';

    protected static ?string $modelLabel= 'Kehadiran';
    
    protected static ?string $slug = 'kehadiran';

    protected static ?string $pluralModelLabel= 'Kehadiran';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 88;

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
                TextColumn::make('tanggal')->label('Tanggal')->date('d-M-Y')->badge()->sortable(),
                TextColumn::make('member.name')->label('Nama'),
                CheckboxColumn::make('hadir')->label('Hadir')->alignCenter()->disabled()
            ])
            ->defaultSort('tanggal','desc')
            ->filters([
                SelectFilter::make('member_id')
                    ->label('Nama Anak')
                    ->options(Member::where('parent_id', Auth::user()->id)->pluck('name','id'))
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('member_id', Member::where('parent_id', Auth::user()->id )->pluck('id')) )
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
            'index' => Pages\ListAbsensis::route('/'),
            // 'create' => Pages\CreateAbsensi::route('/create'),
            // 'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
