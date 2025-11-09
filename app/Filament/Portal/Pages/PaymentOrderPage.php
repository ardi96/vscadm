<?php

namespace App\Filament\Portal\Pages;

use Filament\Pages\Page;

class PaymentOrderPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.portal.pages.payment-order-page';

    protected static bool $shouldRegisterNavigation = false;
}
