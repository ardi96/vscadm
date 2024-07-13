<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassSchedule extends Model
{
    use HasFactory;

    public function location() : BelongsTo
    {
        return $this->belongsTo(ClassLocation::class,'location_id');
    }

    public function package() : BelongsToMany
    {
        return $this->belongsToMany(
            ClassPackage::class,
            ClassPackageSchedule::class
        );
    }
}
