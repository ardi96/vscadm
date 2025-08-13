<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulkInvoiceMember extends Model
{
    use HasFactory;


    public function bulk_invoice() : BelongsTo
    {
        return $this->belongsTo(BulkInvoice::class);
    }

    public function bulkInvoice() : BelongsTo
    {
        return $this->belongsTo(BulkInvoice::class);
    }

    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
