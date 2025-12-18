<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Beasiswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Services\PeriodDropdownService;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BeasiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeasiswaResource\RelationManagers;

class BeasiswaResource extends Resource
{
    protected static ?string $model = Beasiswa::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Beasiswa';

    public static function getPluralLabel(): ?string
    {
        return "Beasiswa";
    }

    protected static ?string $navigationLabel = 'Beasiswa';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Select::make('member_id')
                    ->required()
                    ->options(function () {
                        return \App\Models\Member::all()->pluck('name', 'id');
                    })
                    ->searchable(),
                
                    TextInput::make('biaya')
                    ->label('Iuran Baru per Bulan')
                    ->required()
                    ->suffix('IDR')
                    ->numeric()
                    ->default(0.00),
                
                    Select::make('start_date')
                    ->label('Periode Awal')
                    ->options(PeriodDropdownService::getPeriodOptions(0, 12))
                    ->required(),
                
                    Select::make('end_date')
                    ->label('Periode Akhir')
                    ->options(PeriodDropdownService::getPeriodOptions(0, 12))
                    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Periode Awal')
                    ->date('M-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Periode Akhir')
                    ->date('M-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->numeric()
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),
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
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListBeasiswas::route('/'),
            'create' => Pages\CreateBeasiswa::route('/create'),
            'edit' => Pages\EditBeasiswa::route('/{record}/edit'),
        ];
    }
}
