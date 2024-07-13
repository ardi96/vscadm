<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassLocationResource\Pages;
use App\Filament\Resources\ClassLocationResource\RelationManagers;
use App\Models\ClassLocation;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassLocationResource extends Resource
{
    protected static ?string $model = ClassLocation::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Atur Jadwal dan Lokasi';

    protected static ?string $navigationLabel = 'Lokasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Lokasi')->required()->maxLength(40)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Lokasi'),
                TextColumn::make('created_at')->label('Crated At')->dateTime('d-M-Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClassLocations::route('/'),
        ];
    }
}
