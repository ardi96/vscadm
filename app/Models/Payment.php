<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    public function invoices() : BelongsToMany
    {
        return $this->belongsToMany(Invoice::class,PaymentInvoice::class);
    }

    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
