<?php

namespace App\Filament\Pages;

use App\Models\Member;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Contracts\HasActions;
use Filament\Resources\Pages\CreateRecord;

class UploadRaport extends CreateRecord
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.upload-raport';

    protected static ?string $navigationLabel = 'Upload Raport';

    protected static ?string $slug = 'upload-raport';

    protected static ?string $title = 'Upload Raport';

    protected static ?int $navigationSort = 100;

    protected static ?string $group = 'Reports';    

    protected static ?string $model = Grading::class;

    public ?array $data = [];


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define the form fields for uploading the raport
                // For example, you might want to add a file upload field here
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::all()->pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),
                Fieldset::make('Periode dan Nilai')
                    ->schema([
                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->default(date('Y'))
                            ->required(),
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->selectablePlaceholder(false)
                            ->default(date('n'))
                            ->required(),
                    TextInput::make('marks')
                        ->label('Nilai')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    ])->columnSpan(3)->columns(3),
                FileUpload::make('raport_file')
                    ->directory('raports')
                    ->maxSize(1024 * 5) // 5 MB
            ])->columns(3)
            ->statePath('data');
    }

 
    public function mount(): void
    {
        $this->form->fill();
    }

    public function save(): void
    {

        $this->data = $this->form->getState();

        $member = Member::find($this->data['member_id']);
        
        if (!$member) {
            Notification::make()
            ->title('Member not found')
            ->body('Please select a valid member.')
            ->danger()
            ->send();
            return;
        }


        $this->data['grade_id'] = $member->grade_id;


        $grading = Grading::where('member_id', $this->data['member_id'])
            ->where('year', $this->data['year'])
            ->where('month', $this->data['month'])
            ->first();  

        if ($grading) {
            $grading->update([
                'marks' => $this->data['marks'],
                'raport_file' => $this->data['raport_file'],
            ]);
        } else {
            Grading::create([
                'member_id' => $this->data['member_id'],
                'year' => $this->data['year'],
                'month' => $this->data['month'],
                'marks' => $this->data['marks'],
                'raport_file' => $this->data['raport_file'],
                'grade_id' => $this->data['grade_id'],
                'decision' => 1,
                'notes' => 'Manual Upload',
                'status' => 'approved',
                'approved_by' => auth()->user()->id,
            ]);
        }   

        Notification::make()
            ->title('Raport uploaded successfully')
            ->body('The raport has been saved successfully.')
            ->success()
            ->send();
    }

    // protected function getFormActions(): array
    // {
    //     return [
    //         \Filament\Actions\Action::make('submit')
    //             ->label('Simpan')
    //             ->submit('save')
    //             ->requiresConfirmation(),
    //     ];
    // }
}   
