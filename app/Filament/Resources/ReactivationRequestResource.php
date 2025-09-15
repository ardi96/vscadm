<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ReactivationRequest;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Infolists\Components\ViewPaymentAttachment;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReactivationRequestResource\Pages;
use App\Filament\Resources\ReactivationRequestResource\RelationManagers;
use App\Services\ReactivationService;
use Filament\Notifications\Notification;

class ReactivationRequestResource extends Resource
{
    protected static ?string $model = ReactivationRequest::class;

    protected static ?string $navigationGroup = 'Keanggotaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.id')->label('ID')->formatStateUsing(fn($state) => 'VSC' . str_pad($state, 4, '0', STR_PAD_LEFT))->sortable(),
                TextColumn::make('member.name')->label('Nama Member')->searchable()->sortable(),
                TextColumn::make('amount')->label('Total Pembayaran')->money('IDR', true),
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
                // Tables\Actions\EditAction::make()->visible(fn($record) => $record->status == 0),
                Tables\Actions\ViewAction::make(),
                 ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReactivationRequests::route('/'),
            // 'create' => Pages\CreateReactivationRequest::route('/create'),
            // 'edit' => Pages\EditReactivationRequest::route('/{record}/edit'),
            'view' => Pages\ViewReactivationRequest::route('/{record}'),
        ];
    }
}
