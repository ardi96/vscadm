<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingSourceResource\Pages;
use App\Filament\Resources\MarketingSourceResource\RelationManagers;
use App\Models\MarketingSource;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketingSourceResource extends Resource
{
    protected static ?string $model = MarketingSource::class;

    protected static ?int $navigationSort = 97;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Channel Marketing')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Channel Marketing')
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
            'index' => Pages\ListMarketingSources::route('/'),
            // 'create' => Pages\CreateMarketingSource::route('/create'),
            // 'edit' => Pages\EditMarketingSource::route('/{record}/edit'),
        ];
    }
}
