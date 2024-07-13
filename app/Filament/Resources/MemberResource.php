<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CostumeSize;
use App\Models\ClassPackage;
use App\Models\MarketingSource;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\MemberResource\Pages;
use Filament\Resources\Pages\Page;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Keanggotaan';

    protected static ?string $navigationLabel = 'Daftar Anggota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(40),
                Radio::make('gender')->label('Jenis Kelamin')->required()->options([
                    'L' => 'L', 
                    'P' => 'P'
                ]),
                TextInput::make('parent_name')->label('Nama Orang Tua')->required(),
                TextInput::make('parent_mobile_no')->label('Nomor Whatsapp Aktif Orang Tua')->required(),
                DatePicker::make('date_of_birth')->label('Tanggal Lahir')->required(),
                TextInput::make('school_name')->label('Asal Sekolah')->maxLength(40),
                TextInput::make('costume_label')->label('Nama Tertera di Baju')->maxLength(40),
                Select::make('costume_size_id')->label('Ukuran Baju')->required()->options(
                    CostumeSize::all()->pluck('name','id')
                )->required(),
                Select::make('marketing_source_id')->label('Channel Marketing')->options(
                    MarketingSource::all()->pluck('name','id')
                ),
                TextInput::make('marketing_source_other')->label('Lainnya '),
                TextInput::make('instagram')->label('Nama Akun Instagram'),
                Select::make('class_package_id')->label('Paket Yang Dipilih')
                    ->options(
                        ClassPackage::all()->pluck('name','id')
                    )->required(),
                DatePicker::make('start_date')->label('Mulai Tanggal')->required()
            ])->inlineLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Lengkap'),
                TextColumn::make('gender')->label('Jenis Kelamin'),
                TextColumn::make('date_of_birth')->label('Tanggal Lahir')->date('d-M-Y'),
                TextColumn::make('school_name')->label('Asal Sekolah'),
                TextColumn::make('parent_name')->label('Nama Orang Tua'),
                TextColumn::make('parent_mobile_no')->label('WA Orang Tua'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Invoices')->icon('heroicon-m-banknotes')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\Action::make('Generate Invoice')->icon('heroicon-m-banknotes')->color(Color::Amber),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'view' => Pages\ViewMember::route('/{record}'),
            'invoices' => Pages\MemberInvoices::route('/{record}/invoices'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMember::class,
            Pages\MemberInvoices::class,
        ]);
    }
}
