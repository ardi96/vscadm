<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Payment;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class MonthlyIncome extends ApexChartWidget
{
    protected static ?string $heading = 'Monthly Income';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {

        $year = Carbon::now()->year; // $this->filterFormData['year'];

        $data = Trend::query(Payment::where('status','accepted'))
            ->between(
                start : Carbon::createFromDate($year,1,1),
                end: Carbon::createFromDate($year,12,31)
            )
            ->dateColumn('payment_date')
            ->perMonth()
            ->sum('amount');

        return [
            'chart' => [
                'type' => 'bar',
                // 'stacked' => true,
                'height' => 300,
            ],
            'series' => [
                [
                    'type' => 'bar',
                    'name' => 'Monthly Income (IDR)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate), 
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        // 'fontWeight' => 600,
                    ],
                ],
            ],
            'fill' => [
                // 'colors' => [
                //     '#6366F1',
                //     '#6399FF',
                //     '#AAAA00'
                // ],
                'opacity' => [
                    0.5,
                    0.5,
                    0.5
                ]
            ],
            'stroke' => [
                'show' => true,
                'width' => [1,1,5],
                // 'colors' => 'transparent',
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'dropShadow' => [
                'enabled' => false
            ],
            'markers' => [
                'size' => 5
            ]
        ];
    }
}
