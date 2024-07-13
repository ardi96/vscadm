<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClassPackage;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassPackageResource\Pages;
use App\Filament\Resources\ClassPackageResource\RelationManagers;
use App\Models\ClassSchedule;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ClassPackageResource extends Resource
{
    protected static ?string $model = ClassPackage::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Atur Jadwal dan Lokasi';
    
    protected static ?string $navigationLabel = 'Paket';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Paket')->required()->maxLength(40),
                Textarea::make('description')->label('Deskripsi Paket')->required()->maxLength(140),
                TextInput::make('session_per_week')->label('Sesi per Minggu')->required()->maxValue(21)->suffix('x pertemuan')->numeric(),
                Select::make('type')->label('Tipe Kelas')->options([
                    'private' => 'Private',
                    'regular' => 'Regular',
                    'per Sesi' => 'Per Sesi'
                ]),
                TextInput::make('price')->label('Harga')->numeric()->required()->suffix('IDR'),
                CheckboxList::make('schedules')->relationship('schedules','name')->label('Jadual Kelas')
            ])
            ->inlineLabel()
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Paket'),
                TextColumn::make('description')->label('Deskripsi')->wrap(),
                TextColumn::make('type')->label('Tipe Kelas'),
                TextColumn::make('schedules.name')->label('Jadual')->bulleted(),
                TextColumn::make('price')->label('Harga')->money('IDR'),
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
            'index' => Pages\ListClassPackages::route('/'),
            'create' => Pages\CreateClassPackage::route('/create'),
            'edit' => Pages\EditClassPackage::route('/{record}/edit'),
        ];
    }
}
