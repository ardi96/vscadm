<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ParentResource\Pages;
use App\Filament\Resources\ParentResource\Pages\ParentMembers;
use App\Filament\Resources\ParentResource\Pages\ViewParent;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParentResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Symfony\Component\CssSelector\Node\FunctionNode;

class ParentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Parent';

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Parent';

    protected static ?string $navigationGroup = 'Keanggotaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Lengkap')->required(),
                TextInput::make('email')->label('Email')->required()->unique()->email(),
                TextInput::make('password')->label('Password')
                    ->required()->password()->minLength(8)
                    ->visibleOn('create'),
                TextInput::make('mobile_no')->label('No. WA Aktif')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Lengkap')->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('mobile_no')->label('No. WA Aktif'),
                TextColumn::make('created_at')->label('Tanggal Akun Terdaftar')->dateTime('d-M-Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     // Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('is_admin', 0))
            ->recordUrl( fn(User $record): string => 
                ViewParent::getUrl(['record' => $record])
            );
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
            'index' => Pages\ListParents::route('/'),
            'members' => Pages\ParentMembers::route('/{record}/members'),
            'view' => Pages\ViewParent::route('/{record}'),
            'invoices' => Pages\ParentInvoices::route('/{record}/invoices'),
            // 'create' => Pages\CreateParent::route('/create'),
            // 'edit' => Pages\EditParent::route('/{record}/edit'),
        ];
    }


    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewParent::class,
            Pages\ParentMembers::class,
            Pages\ParentInvoices::class,
        ]);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Start;
    }
}
