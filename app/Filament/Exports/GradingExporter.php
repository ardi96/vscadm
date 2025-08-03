<?php

namespace App\Filament\Exports;

use App\Models\Grading;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class GradingExporter extends Exporter
{
    protected static ?string $model = Grading::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('member.name')
                ->label('Member Name'),
            ExportColumn::make('grade.name')
                ->label('Grade Name'),
            ExportColumn::make('year')
                ->label('Year'),
            ExportColumn::make('month')
                ->label('Month'),
            ExportColumn::make('marks')
                ->label('Nilai'),
            ExportColumn::make('Result')
                ->label('Keputusan'),
            ExportColumn::make('approver.name')
                ->label('Approved By'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your grading export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
