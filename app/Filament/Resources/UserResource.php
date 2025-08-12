<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 99;

    // protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('email')->label('Alamat Email')->email()->required(),
                TextInput::make('password')->label('Password')->password()->visibleOn('create'),
                TextInput::make('mobile_no')->label('No. Handphone')->required(),
                Checkbox::make('is_admin')->label('Back End Access')->default(false)->columnSpanFull(),
                Section::make([
                    CheckboxList::make('roles')->relationship('roles','name')->columnSpanFull()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('email')->label('Alamat Email')->searchable()->sortable(),
                TextColumn::make('roles.name')->label('Peranan')->searchable()->sortable()->bulleted(),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime('d-M-Y H:i:s')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('reset-password')->label('Reset Password')
                    ->action(function (User $record) {
                        
                        $record->update([
                            'password' => Hash::make('Veins@2025!'),
                            'email_verified_at' => now()
                        ]);

                        Notification::make('successful')->body('Password has been reset successfully.')->send();

                    })->requiresConfirmation()
                    ->icon('heroicon-o-key')
                    ->visible( Auth::user()->can('reset password'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
