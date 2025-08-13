<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkInvoice extends Model
{
    use HasFactory;

    public function bulk_invoice_members() : HasMany
    {
        return $this->hasMany(BulkInvoiceMember::class);
    }


    
}
