<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasFactory;

    public function grade() : BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function coach() : BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
