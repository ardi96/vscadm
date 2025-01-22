<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClassLocation;
use App\Models\ClassSchedule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\ClassScheduleResource\Pages;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;

class ClassScheduleResource extends Resource
{
    protected static ?string $model = ClassSchedule::class;

    protected static ?int $navigationSort = 2;

    // protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Atur Jadwal dan Lokasi';

    protected static ?string $navigationLabel = 'Jadwal';

    protected static ?string $label = 'Jadwal';

    public static function getPluralLabel(): ?string
    {
        return "Jadwal";
    }

    public static function getLabel(): ?string
    {
        return "Jadwal";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Jadwal')->required()->maxLength(40),
                
                Select::make('location_id')->label('Lokasi')->options(ClassLocation::all()->pluck('name','id'))->required(),
                Select::make('schedule_day')->label('Hari')->options([
                    'Minggu' => 'Minggu',
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                ])->required(),
                TimePicker::make('schedule_start_time')->label('Waktu Mulai')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Jadwal')->searchable()->sortable(),
                TextColumn::make('location.name')->label('Lokasi')->searchable()->sortable(),
                TextColumn::make('schedule_day')->label('Hari')->searchable()->sortable(),
                TextColumn::make('schedule_start_time')->label('Waktu Mulai')->searchable()->sortable(),
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
            'index' => Pages\ManageClassSchedules::route('/'),
        ];
    }
}
