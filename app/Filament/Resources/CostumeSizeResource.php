<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostumeSizeResource\Pages;
use App\Filament\Resources\CostumeSizeResource\RelationManagers;
use App\Models\CostumeSize;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostumeSizeResource extends Resource
{
    protected static ?string $model = CostumeSize::class;

    protected static ?int $navigationSort = 98;

    // protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Ukuran')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Ukuran')
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
            'index' => Pages\ListCostumeSizes::route('/'),
            // 'create' => Pages\CreateCostumeSize::route('/create'),
            // 'edit' => Pages\EditCostumeSize::route('/{record}/edit'),
        ];
    }
}
