<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use Filament\Actions;
use App\Models\Payment;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\MemberResource;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['parent_id'] = Auth()->user()->id;

        return $data; 
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }

    public function afterCreate() : void 
    {
        $record = $this->record;

        if ( $record->payment_file_name != null && $record->payment_amount != null )
        {
            Payment::create([
                'user_id' => Auth::user()->id,
                'member_id' => $record->id,
                'amount' => $record->payment_amount,
                'payment_date' => Date::now(),
                'notes' => 'Pendaftaran a.n. ' . $record->name ,
                'bank' => 'N/A',
                'file_name' => $record->payment_file_name,
                'status' => 'pending'
            ]);
        }
    }


    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            // ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Kirim Data Registrasi')
            // ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Registrasi Baru';
    }
    
    
}
