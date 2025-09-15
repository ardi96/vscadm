<?php

namespace App\Filament\Portal\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Resignation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\ResignationResource\Pages;
use App\Filament\Portal\Resources\ResignationResource\RelationManagers;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;

class ResignationResource extends Resource
{
    protected static ?string $model = Resignation::class;

    protected static ?string $navigationIcon = 'heroicon-o-stop-circle';
    
    protected static ?string $label = 'Pengunduran Diri';

    protected static ?string $pluralModelLabel = 'Pengunduran Diri';

    protected static ?string $navigationLabel = 'Pengunduran Diri';

    protected static ?int $navigationSort = 90;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('member.name')->label('Nama Member'),
            TextEntry::make('resignation_date')->label('Tanggal Efektif')->date(),
            TextEntry::make('reason')->label('Alasan Pengunduran Diri'),
            TextEntry::make('status')->label('Status')->formatStateUsing(function ($state) {
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
            ])->badge(),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Nama Member')
                    ->relationship('member', 'name')
                    ->options(Member::where('parent_id', auth()->user()->id)->pluck('name', 'id'))
                    ->required()
                    ->rules([new \App\Rules\ResignationPending(), new \App\Rules\InvoiceOutstanding()]),
                TextInput::make('reason')
                    ->label('Alasan Pengunduran Diri')
                    ->maxLength(255)
                    ->required()
                    ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')->label('Nama Member')->searchable()->sortable(),
                TextColumn::make('resignation_date')->label('Tanggal Efektif')->date()->sortable(),
                TextColumn::make('reason')->label('Alasan')->limit(50),
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
                ])->sortable()->badge()
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListResignations::route('/'),
            'create' => Pages\CreateResignation::route('/create'),
            'edit' => Pages\EditResignation::route('/{record}/edit'),
        ];
    }
}
