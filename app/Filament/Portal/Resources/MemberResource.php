<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Kelas;
use App\Models\Member;
use Filament\Forms\Get;
use Filament\Forms\Form;
// use Filament\Support\RawJs;
use Filament\Tables\Table;
use App\Models\CostumeSize;
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
use App\Rules\MemberUniqueValidation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
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
use App\Models\GlobalParameter;
use Filament\Forms\Components\Placeholder;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Member';

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
                    TextInput::make('parent_mobile_no')->label('No. WA Aktif Orang Tua')->required(),
                    DatePicker::make('date_of_birth')->label('Tanggal Lahir')->required()->rule(new MemberUniqueValidation()),
                    TextInput::make('school_name')->label('Asal Sekolah')->maxLength(40),
                    TextInput::make('costume_label')->label('Nama Tertera di Baju')->maxLength(40)->required(),
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

                    // Select::make('grade_id')->options(Grade::all()->pluck('name','id'))->label('Grade'),
                    Select::make('kelas_id')->options(Kelas::all()->pluck('name','id'))->label('Kelas'),
                    
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
                            // ->rules([new ClassScheduleValidation()])
                            
                        ]),
                ])
                ->columns(4)
                ->columnSpanFull(),
                Wizard\Step::make('Instruksi Pembayaran')->schema([
                    Placeholder::make('Instruksi_pembayaran')->label('')
                    ->content( function() { return new HtmlString('
                        <p class=\'font-bold\'>Instruksi Pembayaran</p>
                        <ul>
                            <li>Biaya pendaftaran adalah Rp '. number_format(GlobalParameter::where('parameter_key','BIAYA_REGISTRASI')->first()->decimal_value,0,',','.') .' </li>
                            <li>Klik tombol Kirim Data Registrasi, sistem akan mengarahkan Anda ke halaman checkout</li>
                            <li>Selesaikan pembayaran dalam waktu 60 menit</li>
                        </ul>
                    ');} )
                ]),
                Wizard\Step::make('Pembayaran')->schema([
                    Select::make('payment_mode')->label('Pilih metode pembayaran')
                        ->options([
                            1 => 'Online Payment',
                            0 => 'Manual (upload bukti bayar)'
                        ])
                        ->default(0)
                        ->required()
                        ->live()
                        ->selectablePlaceholder(false),
                    TextInput::make('payment_amount')->label('Jumlah')
                        ->suffix('IDR')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->visible(function(Get $get) { return $get('payment_mode') == 0;})
                        // ->default(250000)
                        // ->readOnly()
                        //->mask(RawJs::make('$money($input,\',\',\'.\')'))
                        ,
                
                    TextInput::make('bank')->label('Bank')->required()->visible(function(Get $get) { return $get('payment_mode') == 0;}),
                    TextInput::make('notes')->label('Keterangan')->required()->visible(function(Get $get) { return $get('payment_mode') == 0;}),
                    DatePicker::make('payment_date')->label('Tanggal Transfer')->required()->default(Date::now())->visible(function(Get $get) { return $get('payment_mode') == 0;}),

                    FileUpload::make('payment_file_name')->label('Upload File')
                            ->visible(function(Get $get) { return $get('payment_mode') == 0;})
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
                ])->visibleOn([])
            ])->columnSpanFull(),
        ])->inlineLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->label('ID')->searchable()->sortable()->formatStateUsing(fn($state) : string => Member::formatMemberId($state)),
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
                Tables\Actions\Action::make('reactivate')
                    ->label('Reaktivasi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status == 'resigned')
                    ->action(function (Member $record, Livewire $livewire) {
                        
                        $record->status = 'active';
                        $record->save();

                        $livewire->notify('success', 'Member diaktifkan kembali');
                    }),
            ])
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
            ]),
        ])
        ->emptyStateHeading('Anda belum mendaftar. Silakan klik tombol Registrasi Baru di kanan atas')
        ->modifyQueryUsing(fn (Builder $query) => $query->where('parent_id', Auth::user()->id))
        ->defaultSort('id','desc');
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
