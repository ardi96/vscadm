<?php

namespace App\Filament\Exports;

use App\Models\Member;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MemberExporter extends Exporter
{
    protected static ?string $model = Member::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('school_name'),
            ExportColumn::make('gender'),
            ExportColumn::make('date_of_birth'),
            ExportColumn::make('parent_name'),
            ExportColumn::make('parent_mobile_no'),
            ExportColumn::make('costume_size_id'),
            ExportColumn::make('costume_label'),
            ExportColumn::make('marketing_source_other'),
            ExportColumn::make('instagram'),
            ExportColumn::make('class_package_id'),
            ExportColumn::make('schedules'),
            ExportColumn::make('start_date'),
            ExportColumn::make('status'),
            ExportColumn::make('last_invoice_date'),
            ExportColumn::make('last_payment_date'),
            ExportColumn::make('payment_file_name'),
            ExportColumn::make('payment_amount'),
            ExportColumn::make('balance'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('grade_id'),
            ExportColumn::make('kelas_id')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your member export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
