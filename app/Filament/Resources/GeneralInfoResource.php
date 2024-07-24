<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralInfoResource\Pages;
use App\Filament\Resources\GeneralInfoResource\RelationManagers;
use App\Models\GeneralInfo;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GeneralInfoResource extends Resource
{
    protected static ?string $model = GeneralInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MarkdownEditor::make('info')->required()->columnSpanFull()->maxHeight('400px')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('info')->wrap()->lineClamp(4)
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
            'index' => Pages\ListGeneralInfos::route('/'),
            'create' => Pages\CreateGeneralInfo::route('/create'),
            'edit' => Pages\EditGeneralInfo::route('/{record}/edit'),
            'view' => Pages\ViewGeneralInfo::route('/{record}'),
        ];
    }
}
