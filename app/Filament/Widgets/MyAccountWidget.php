<?php

namespace App\Filament\Widgets;

// use Filament\Widgets\Widget;
use Filament\Widgets\AccountWidget;

class MyAccountWidget extends AccountWidget
{
    // protected static string $view = 'filament.widgets.my-account-widget';
    protected int | string | array $columnSpan = 'full';
}
