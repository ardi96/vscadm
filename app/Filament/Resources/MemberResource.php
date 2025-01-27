<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Kelas;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CostumeSize;
use App\Models\ClassPackage;
use App\Models\ClassSchedule;
use App\Models\MarketingSource;
use App\Services\InvoiceService;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use App\Models\ClassPackageSchedule;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\MemberResource\Pages;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    // protected static ?string $navigationIcon = 'heroicon-o-users';

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
                ])->columns(2),
                TextInput::make('parent_name')->label('Nama Orang Tua')->required(),
                TextInput::make('parent_mobile_no')->label('Nomor WA Aktif Orang Tua')->required(),
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
                
                // Select::make('kelas_id')->options(Kelas::all()->pluck('name','id'))->required()->label('Kelas'),
                Select::make('grade_id')->options(Grade::all()->pluck('name','id'))->label('Grade'),
                
                Select::make('class_package_id')->label('Paket Yang Dipilih')
                    ->options(
                        ClassPackage::all()->pluck('name','id')
                    )->required()->live(),
                    
                Select::make('status')->options([
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                        'active' => 'Active'    
                     ])->required()
                    ->label('Status'),
                Section::make('Pilih Jadwal')
                    ->schema([
                        CheckboxList::make('jadwal')->relationship('schedules')->options(function (Forms\Get $get) {
                            return ClassSchedule::whereIn(
                                'id',ClassPackageSchedule::where('class_package_id', $get('class_package_id'))->pluck('class_schedule_id')
                                )->pluck('name','id');
                            })
                ]),
                Section::make('Parent')->schema([
                    Select::make('parent_id')->options(
                        User::all()->pluck('name','id')
                    )->required()
                    ->label('Link ke Orang Tua')
                ])->columnSpanFull()
            ])->inlineLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Lengkap')->searchable()->sortable(),
                TextColumn::make('gender')->label('J/K')->alignCenter()->searchable()->sortable(),
                TextColumn::make('date_of_birth')->label('Tanggal Lahir')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('school_name')->label('Asal Sekolah')->searchable()->sortable(),
                TextColumn::make('parent.name')->label('Nama Orang Tua')->searchable()->sortable(),
                TextColumn::make('parent.mobile_no')->label('WA Orang Tua')->searchable()->sortable(),
                TextColumn::make('balance')->label('Outstanding')->money('IDR')->searchable()->sortable(),
                TextColumn::make('status')->label('Status')->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'inactive' => 'Inactive',
                    'active' => 'Active',
                ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make()
                    //     ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('Generate Monthly Invoice')
                        ->icon('heroicon-m-banknotes')
                        ->color(Color::Amber)
                        ->requiresConfirmation()
                        ->action(function(Collection $records) 
                            { 
                                foreach($records as $member)
                                {
                                    InvoiceService::generate( $member );
                                }
                            })
                        ->deselectRecordsAfterCompletion(),
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
