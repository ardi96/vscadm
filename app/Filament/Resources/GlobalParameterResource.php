<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlobalParameterResource\Pages;
use App\Filament\Resources\GlobalParameterResource\RelationManagers;
use App\Models\GlobalParameter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GlobalParameterResource extends Resource
{
    protected static ?string $model = GlobalParameter::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 110;

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('parameter_key')
                    ->required()
                    ->readOnlyOn('edit')
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('string_value')
                    ->maxLength(255),
                Forms\Components\TextInput::make('int_value')
                    ->numeric(),
                Forms\Components\Toggle::make('bool_value'),
                Forms\Components\DatePicker::make('date_value'),
                Forms\Components\TextInput::make('decimal_value')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parameter_key')->label('Key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('string_value')->label('String Value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('int_value')->label('Integer Value')
                    ->numeric()->alignEnd(),
                Tables\Columns\IconColumn::make('bool_value')->label('Boolean Value')
                    ->boolean()->alignCenter(),
                Tables\Columns\TextColumn::make('date_value')->label('Date Value')
                    ->date(),
                Tables\Columns\TextColumn::make('decimal_value')->label('Decimal Value')
                    ->numeric()->alignEnd(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListGlobalParameters::route('/'),
            // 'create' => Pages\CreateGlobalParameter::route('/create'),
            // 'edit' => Pages\EditGlobalParameter::route('/{record}/edit'),
        ];
    }
}
