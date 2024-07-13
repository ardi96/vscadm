<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassSessionResource\Pages;
use App\Filament\Resources\ClassSessionResource\RelationManagers;
use App\Models\ClassSession;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Atur Jadwal dan Lokasi';

    protected static ?string $navigationLabel = 'Session';

    protected static bool $shouldRegisterNavigation = false;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Sesi')->required()->maxLength(40),
                TextInput::make('session_per_week')->label('Sesi per Minggu')->required()->maxValue(21)->suffix('x pertemuan')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Sesi'),
                TextColumn::make('session_per_week')->label('Sesi Per Minggu')->alignCenter(true),
                TextColumn::make('created_at')->label('Created At')->dateTime('d-M-Y H:i:s'),
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
            'index' => Pages\ManageClassSessions::route('/'),
        ];
    }
}
