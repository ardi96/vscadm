<?php

namespace App\Filament\Exports;

use App\Models\Invoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('member_id')
                ->label('Nama Member'),
            ExportColumn::make('parent_id')
                ->label('Nama Orang Tua'),
            ExportColumn::make('invoice_no')
                ->label('No. Invoice'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
            ExportColumn::make('amount')
                ->label('Jumlah'),
            ExportColumn::make('type')
                ->label('Jenis'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('description')
                ->label('Keterangan')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your invoice export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
