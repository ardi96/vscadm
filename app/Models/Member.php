<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    public function marketingSource() : BelongsTo
    {
        return $this->belongsTo(MarketingSource::class,'marketing_source_id');
    }

    public function package() : BelongsTo
    {
        return $this->belongsTo(ClassPackage::class,'class_package_id');
    }

    public function invoices() : HasMany
    {
        return $this->hasMany(Invoice::class,'member_id');
    }
}
