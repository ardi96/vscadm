<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Leave;
use Filament\Forms\Form;
use App\Rules\EndOfMonth;
use Filament\Tables\Table;
use App\Rules\FirstOfMonth;
use App\Models\GlobalParameter;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Services\PeriodDropdownService;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LeaveResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LeaveResource\RelationManagers;
use App\Services\LeaveService;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $label = 'Cuti';

    protected static ?string $pluralModelLabel = 'Cuti';

    protected static ?string $navigationLabel = 'Cuti';

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Keanggotaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Nama Member')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->required(),
                Select::make('start_date')
                    ->label('Periode Cuti')
                    ->options(PeriodDropdownService::getPeriodOptions(-1, GlobalParameter::where('parameter_key', 'MAX_CUTI_PER_TAHUN')->first()->int_value))
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        $start_date = $get('start_date');
                        $end_date = $get('end_date');
                        $total_biaya = LeaveService::getBiayaCuti($start_date, $end_date);
                        $set('biaya', $total_biaya);
                    }),
                Select::make('end_date')
                    ->label('Sampai Dengan')
                    ->options(PeriodDropdownService::getPeriodOptions(-1, GlobalParameter::where('parameter_key', 'MAX_CUTI_PER_TAHUN')->first()->int_value))
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        $start_date = $get('start_date');
                        $end_date = $get('end_date');
                        $total_biaya = LeaveService::getBiayaCuti($start_date, $end_date);
                        $set('biaya', $total_biaya);
                    }),
                Forms\Components\TextInput::make('biaya')
                    ->label('Biaya')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('hitung_biaya')
                            ->icon('heroicon-o-calculator')
                            ->label('Hitung Biaya')
                            ->action(function (Forms\Get $get, Forms\Set $set) {

                                $start_date = $get('start_date');
                                $end_date = $get('end_date');
                                $total_biaya = LeaveService::getBiayaCuti($start_date, $end_date);
                                $set('biaya', $total_biaya);
                            })
                    ),
                Forms\Components\FileUpload::make('file_name')
                    ->label('Bukti Bayar')
                    ->required()
                    ->disk('public')
                    ->directory('leave_files')
                    ->columnSpan(2)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.id')->label('ID')->formatStateUsing(fn($state) => 'VSC' . str_pad($state, 4, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('member.name')->label('Nama Member')->searchable()->sortable(),
                TextColumn::make('start_date')->label('Periode Cuti')->date('M-Y')->sortable(),
                TextColumn::make('end_date')->label('Sampai')->date('M-Y')->sortable(),
                TextColumn::make('biaya')->label('Biaya')->money('IDR', true)->sortable(),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime()->sortable(),
                TextColumn::make('status')->label('Status')->formatStateUsing(function ($state) {
                    return match ($state) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                        default => 'Unknown',
                    };
                })->colors([
                    'warning' => 0,
                    'success' => 1,
                    'danger' => 2,
                ])->sortable()->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn($record) => $record->status == 0),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
            'view' => Pages\ViewLeave::route('/{record}'),
        ];
    }
}
