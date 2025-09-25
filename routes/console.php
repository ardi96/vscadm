<?php

use App\Services\InvoiceService;
use App\Services\ProcessResignation;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\GenerateMonthlyInvoice;
use Illuminate\Support\Facades\Schedule;
use App\Services\GeneratePreviousInvoice;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(new GenerateMonthlyInvoice())->monthlyOn(25,'21:00');

Schedule::call(new ProcessResignation())->dailyAt('00:10');

// The package price can be different, so we don't retrieve
// the package price from master data, instead we define in this 
// function directly.

// -->  7/9/2025 nggak jadi karena sudah dibuat manual oleh Admin 

// Artisan::command('invoice:generate {year} {month}', function ($year, $month) {
    
//     $service = app(GeneratePreviousInvoice::class);

//     $service($year, $month);

// })->purpose('Generate monthly invoices for all members for a given year and month');