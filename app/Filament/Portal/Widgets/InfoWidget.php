<?php

namespace App\Filament\Portal\Widgets;

use App\Models\GeneralInfo;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class InfoWidget extends Widget
{
    protected ?Model $record = null;
     
    protected static string $view = 'filament.portal.widgets.info-widget';

    protected int | string | array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        if ( GeneralInfo::all()->isEmpty() ) {
            return [
                'record' =>  ''
            ];
        }   
        else
        {
            return [
                'record' =>  GeneralInfo::all()->first()->info
            ];
        }
    }
}
