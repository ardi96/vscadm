<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CostumeSize;
use Filament\Support\RawJs;
use App\Models\ClassPackage;
use App\Models\ClassSchedule;
use App\Models\MarketingSource;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use App\Models\ClassPackageSchedule;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use App\Rules\ClassScheduleValidation;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\MemberResource\Pages;
use App\Filament\Portal\Resources\MemberResource\RelationManagers;


class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Registrasi';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Isi Bio Data')->schema([
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
                        )
                        ->required()
                        ->hintAction(
                            Action::make('display')
                                ->label('')
                                ->modalContent(view('filament.portal.pages.costume-size'))
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Close')
                                ->icon('heroicon-o-question-mark-circle')
                                ->tooltip('Ukuran Baju')
                        ),
                    Select::make('marketing_source_id')->label('Channel Marketing')->options(
                        MarketingSource::all()->pluck('name','id')
                    )->live()->required(),
                    TextInput::make('marketing_source_other')->label('Lainnya ')
                        ->visible(fn(Forms\Get $get) => ($get('marketing_source_id') ===  '4'))
                        ->requiredIf('marketing_source_id','4')
                        ->validationMessages(['required_if' => 'Isi channel marketing lainnya']),
                    TextInput::make('instagram')->label('Nama Akun Instagram'),
                ])->columns(2),

                Wizard\Step::make('Pilih Paket')->schema([
                        Radio::make('class_package_id')->label('')
                            ->options(
                                ClassPackage::all()->pluck('name','id')
                            )
                            ->descriptions(
                                ClassPackage::all()
                                    ->pluck('description','id')
                            )
                            ->required()
                            ->live()
                            ->columns(4)
                            // ->columnSpan(4)
                ]),
                
                Wizard\Step::make('Pilih Jadwal')->schema([
                    Section::make('Pilih Jadwal')
                        ->schema([
                            CheckboxList::make('schedules')->label('')
                                ->relationship('schedules')
                                ->options(function (Forms\Get $get) {
                                return ClassSchedule::whereIn(
                                    'id',ClassPackageSchedule::where('class_package_id', $get('class_package_id'))->pluck('class_schedule_id')
                                    )->pluck('name','id');
                                })
                            ->columns(2)
                            ->rules([new ClassScheduleValidation()])
                            
                        ]),
                ])
                ->columns(4)
                ->columnSpanFull(),

                Wizard\Step::make('Kirim Bukti Pembayaran')->schema([
                    TextInput::make('payment_amount')->label('Jumlah')
                        ->suffix('IDR')
                        ->numeric()
                        ->required()
                        ->default(150000)
                        ->readOnly()
                        ->mask(RawJs::make('$money($input,\',\',\'.\')')),
                
                    TextInput::make('bank')->label('Bank')->required(),
                    TextInput::make('notes')->label('Keterangan')->required(),
                    DatePicker::make('payment_date')->label('Tanggal Transfer')->required()->default(Date::now()),

                    FileUpload::make('payment_file_name')->label('Upload File')
                            ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                            ->maxSize(1024*2)
                            ->required()
                            ->hintAction(
                                Action::make('petunjuk')
                                    ->label('Petunjuk Pembayaran')
                                    ->modalContent(view('filament.portal.pages.petunjuk-pembayaran'))
                                    ->modalSubmitAction(false)
                                    ->modalCancelActionLabel('Close')
                                    ->icon('heroicon-o-question-mark-circle')
                                    ->tooltip('Petunjuk Pembayaran')
                            ),
                ])
            ])->columnSpanFull(),
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
            TextColumn::make('parent_name')->label('Nama Orang Tua')->searchable()->sortable(),
            TextColumn::make('parent_mobile_no')->label('WA Orang Tua')->searchable()->sortable(),
            TextColumn::make('balance')->label('Outstanding')->money('IDR')->searchable()->sortable(),
            TextColumn::make('status')->label('Status')->searchable()->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([ 
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\EditAction::make()->visible(fn($record) => $record->status == 'pending'),
            ])
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
            ]),
        ])
        ->emptyStateHeading('Anda belum mendaftar. Silakan klik tombol Registrasi Baru di kanan atas')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('parent_id', Auth::user()->id));
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
        ];
    }
}
