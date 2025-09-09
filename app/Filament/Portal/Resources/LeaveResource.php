<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Leave;
use App\Models\Member;
use Filament\Forms\Form;
use App\Rules\EndOfMonth;
use Filament\Tables\Table;
use App\Rules\FirstOfMonth;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\LeaveResource\Pages;
use App\Filament\Portal\Resources\LeaveResource\RelationManagers;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $label = 'Cuti';

    protected static ?string $pluralModelLabel = 'Cuti';

    protected static ?string $navigationLabel = 'Cuti';

    protected static ?string $navigationIcon = 'heroicon-o-pause-circle';

    protected static ?int $navigationSort = 89;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Nama Member')
                    ->relationship('member', 'name')
                    ->options(Member::where('parent_id', auth()->user()->id)->pluck('name', 'id'))
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Periode Cuti')
                    ->afterOrEqual(now()->format('Y-m-d'))
                    ->rules([new FirstOfMonth()])
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Sampai Dengan')
                    ->rules([ new EndOfMonth()])
                    ->after('start_date')
                    ->required(),
                Forms\Components\TextInput::make('biaya')
                    ->label('Biaya')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\FileUpload::make('file_name')
                    ->label('Bukti Bayar')
                    ->required()
                    ->disk('public')
                    ->directory('leave_files')
                    ->columnSpan(3)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.id')->label('ID')->formatStateUsing(fn($state) => 'VSC' . str_pad($state, 4, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('member.name')->label('Nama Member')->searchable()->sortable(),
                TextColumn::make('start_date')->label('Periode Cuti')->date()->sortable(),
                TextColumn::make('end_date')->label('Sampai')->date()->sortable(),
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
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('member_id', Member::where('parent_id', auth()->user()->id)->pluck('id')));
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
            'view' => Pages\ViewLeave::route('/{record}')
        ];
    }
}
