<?php

namespace App\Filament\Coach\Resources\GradingResource\Pages;

use App\Filament\Coach\Resources\GradingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGrading extends EditRecord
{
    protected static string $resource = GradingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $record->update($data);
 
    //     $items = $record->gradingItems();

    //     $marks = 0; 

    //     foreach($items as $item)
    //     {
    //         $marks += $item->mark;
    //     }

    //     $record->marks = $marks;

    //     $record->save();

    //     return $record;    
    // }

    protected function afterSave() : void
    {
        parent::afterSave();

        $marks = 0; 

        $record = $this->getRecord();

        $items = $record->gradingItems();

        foreach($items as $item)
        {
            $marks += $item->mark;
        }

        $record->marks = $marks;

        $record->save();
    }

}
