<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ClassPackage;
use App\Models\ClassSchedule;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassPackageResource\Pages;
use App\Filament\Resources\ClassPackageResource\RelationManagers;
use Filament\Support\Enums\VerticalAlignment;

class ClassPackageResource extends Resource
{
    protected static ?string $model = ClassPackage::class;

    protected static ?int $navigationSort = 3;

    // protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Atur Jadwal dan Lokasi';
    
    protected static ?string $navigationLabel = 'Paket';

    protected static ?string $label = 'Package';

    protected static ?string $slug = 'Packages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Paket')->required()->maxLength(40),
                Textarea::make('description')->label('Deskripsi Paket')->required()->maxLength(255),
                Select::make('type')->label('Tipe Kelas')
                    ->required()
                    ->options([
                    'private' => 'Private',
                    'regular' => 'Regular',
                    'per sesi' => 'Per Sesi'
                ])->live(),
                TextInput::make('session_per_week')->label('Sesi per Bulan')
                    ->hidden(fn(Forms\Get $get): bool => $get('type') == 'private' || $get('type') =='per sesi')
                    ->requiredIf('type','regular')
                    ->maxValue(21)->suffix('pertemuan')
                    ->numeric(),
                TextInput::make('price')->label('Harga')->numeric()->required()->suffix('IDR'),
                TextInput::make('price_per_session')->label('Harga per Sesi')->numeric()->required()->suffix('IDR'),
                CheckboxList::make('schedules')->relationship('schedules','name')->label('Jadwal Tersedia')
                    ->columns(1)
                    ->bulkToggleable()
            ])
            ->inlineLabel()
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Paket')->searchable()->sortable()->verticallyAlignStart()
                    ->description(fn($record) => $record->description)->wrap(),
                // TextColumn::make('description')->label('Deskripsi')->wrap()->searchable()->sortable()->verticallyAlignStart(),
                TextColumn::make('type')->label('Tipe Kelas')->searchable()->sortable()->verticallyAlignStart(),
                TextColumn::make('schedules.name')->label('Jadwal Tersedia')
                    ->verticalAlignment(VerticalAlignment::Start)
                    ->bulleted()->searchable()->sortable(),
                TextColumn::make('price')->label('Harga')->money('IDR')->searchable()->sortable()->verticallyAlignStart(),
                TextColumn::make('price_per_session')->label('Harga/Sesi')->money('IDR')->searchable()->sortable()->verticallyAlignStart(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible( Auth::user()->can('delete paket')),
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
