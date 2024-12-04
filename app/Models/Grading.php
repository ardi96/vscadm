<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grading extends Model
{
    use HasFactory;

    public function gradingItems() : HasMany
    {
        return $this->hasMany(GradingItem::class, 'grading_id');
    }
}
