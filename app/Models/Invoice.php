<?php

namespace App\Models;

use App\Observers\InvoiceObserver;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    use HasFactory;

    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(User::class,'parent_id');
    }

    public function payNow() : void
    {
        $this->status = 'paid';
        $this->payment_date = Date::now();
        $this->save();

        $this->member->balance = $this->member->balance - $this->amount;
        $this->member->last_payment_date = Date::now();
        $this->member->save();

        $iuranBulananMember = IuranBulananMember::where('invoice_id',$this->id)->first();
        
        if( $iuranBulananMember ) 
        {
            $iuranBulananMember->status = 'paid';
            $iuranBulananMember->save();    
        }
                       
    }

    public function cancelPayment() : void
    {
        $this->status = 'unpaid';
        $this->payment_date = Date::now();
        $this->save();

        // $this->member->balance = $this->member->balance + $this->amount;
        // $this->member->save();
                       
    }


    public function cancel() : void
    {
        $this->status = 'void';
        $this->save();

        $this->member->balance = $this->member->balance - $this->amount;
        $this->member->save();
    }

    public function payment() : BelongsToMany
    {
        return $this->belongsToMany(Payment::class,PaymentInvoice::class);
    }

    public function items() : HasMany {
        return $this->hasMany(InvoiceItem::class);
    }
    
}
