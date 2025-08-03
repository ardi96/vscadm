<?php

namespace App\Models;

use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grading extends Model
{
    use HasFactory;

    public function gradingItems() : HasMany
    {
        return $this->hasMany(GradingItem::class, 'grading_id');
    }

    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function grade() : BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function approver() :BelongsTo
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function getResultAttribute()
    {
        $decision = $this->decision; 

        if ( $decision == 1 ) 
            return 'Lulus';
        else 
            return 'Tidak Lulus';

    }
}
