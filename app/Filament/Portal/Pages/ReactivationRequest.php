<?php

namespace App\Filament\Portal\Pages;

use App\Models\Member;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\ReactivationRequest as ModelReactivationRequest;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class ReactivationRequest extends Page implements HasForms
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    
    protected static ?string $label = 'Reaktivasi';

    protected static ?string $pluralModelLabel = 'Reaktivasi';

    protected static ?string $navigationLabel = 'Reaktivasi';

    protected static ?int $navigationSort = 91;

    protected static string $view = 'filament.portal.pages.reactivation-request';


    public ?array $data = []; 


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Pilih Member')
                    ->options(Member::where('parent_id', Auth::user()->id)
                                      ->where('status', 'resigned')->pluck('name','id'))
                    ->required()
                    ,
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->required()
                    ->label('Jumlah Transfer'),
                TextInput::make('bank')
                    ->required(),
                TextInput::make('notes')
                    ->label('Keterangan'),
                FileUpload::make('file_name')
                    ->disk('public')
                    ->label('Bukti Transfer')
                    ->directory('reactivation_files')
                    ->columnSpan(2)
                    ->required()
            ])->columns(3)
            ->statePath('data');
    }


    public function mount(): void
    {
        $this->form->fill();
    }

    public function save(): void
    {
        try {
            
            $data = $this->form->getState();

            $data['user_id'] = auth()->user()->id;
            $data['status'] = 0;
            $data['approver_id'] = null;
  
            \App\Models\ReactivationRequest::create( $data );

            // auth()->user()->company->update($data);
        
        } catch (Halt $exception) {
            return;
        }
 
        Notification::make() 
            ->success()
            ->title('Permintaan Reaktivasi Sukses')
            ->body('Tunggu update dari Admin untuk status reaktivasi Anda')
            ->persistent() 
            ->send();
        
        redirect($this->getUrl());
    }
}
