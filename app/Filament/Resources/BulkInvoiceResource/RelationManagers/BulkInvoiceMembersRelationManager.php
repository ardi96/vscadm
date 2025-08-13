<?php

namespace App\Filament\Resources\BulkInvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BulkInvoiceMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'bulk_invoice_members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required()
                    ->searchable()
                    ->label('Member'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('member.id')->formatStateUsing( fn($state) => 
                            'VSC' . substr('000'.$state, -4))->label('Member ID'),
                Tables\Columns\TextColumn::make('member.name')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('member.kelas.name')->label('Kelas'),
                Tables\Columns\TextColumn::make('member.parent.name')->label('Nama Orang Tua'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Anggota'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
