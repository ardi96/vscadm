<?php

namespace App\Filament\Resources\ParentResource\Pages;

use App\Filament\Resources\MemberResource\Pages\ViewMember;
use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ParentResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParentMembers extends ManageRelatedRecords
{
    protected static string $resource = ParentResource::class;

    protected static string $relationship = 'members';

    protected static ?string $title = 'Members';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'Members';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')->label('Nama Lengkap'),
            TextEntry::make('email')->label('Email'),
            TextEntry::make('mobile_no')->label('No. WA Aktif'),
            TextEntry::make('created_at')->label('Tanggal Register')->dateTime('d-M-Y H:i:s'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('gender')->label('J/K')->alignCenter(),
                Tables\Columns\TextColumn::make('date_of_birth')->label('Tanggal Lahir')->date('d-M-Y'),
                Tables\Columns\TextColumn::make('school_name')->label('Asal Sekolah'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('balance')->label('Outstanding')->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DissociateAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl( fn(Member $record): string => 
                ViewMember::getUrl(['record' => $record])
            );
    }
}
