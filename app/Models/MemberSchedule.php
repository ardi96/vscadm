<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MemberSchedule extends Model
{
    use HasFactory;

    public function member() : BelongsToMany
    {
        return $this->belongsToMany(Member::class,'member_schedules');
    }

}
