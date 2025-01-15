<?php

namespace App\Filament\Coach\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use Filament\Forms\Form;
use App\Models\SesiKelas;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Coach\Resources\SesiKelasResource\Pages;
use App\Filament\Coach\Resources\SesiKelasResource\RelationManagers;

class SesiKelasResource extends Resource
{
    protected static ?string $model = SesiKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal')->label('Tanggal')->required()->default(Carbon::now()),
                Select::make('kelas_id')->label('Kelas')->options(Kelas::pluck('name','id'))->required(),
                TextInput::make('coach')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')->date('d-M-Y')->badge()->searchable()->sortable(),
                TextColumn::make('kelas.name')->searchable()->sortable(),
                TextColumn::make('coach')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSesiKelas::route('/'),
            // 'create' => Pages\CreateSesiKelas::route('/create'),
            // 'edit' => Pages\EditSesiKelas::route('/{record}/edit'),
        ];
    }
}
