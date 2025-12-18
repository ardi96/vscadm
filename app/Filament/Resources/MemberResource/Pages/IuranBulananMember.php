<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IuranBulananMember extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'iuranBulananMembers';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $title = 'Status Iuran Bulanan';

    public static function getNavigationLabel(): string
    {
        return 'Status Iuran Bulanan';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_id')
            ->columns([
                Tables\Columns\TextColumn::make('period_year')
                    ->label('Periode')
                    ->formatStateUsing(fn($record) => date('M-Y', strtotime($record->period_year . '-' . $record->period_month . '-01'))  ),
                Tables\Columns\TextColumn::make('invoice.invoice_no')->label('Nomor Invoice'),
                Tables\Columns\TextColumn::make('status')->badge(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
