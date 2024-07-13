<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassPackage extends Model
{
    use HasFactory;

    // public function schedules() : HasMany
    // {
    //     return $this->hasMany(ClassPackageSchedule::class,'class_package_id');
    // }

    public function schedules() : BelongsToMany
    {
        return $this->belongsToMany(
            ClassSchedule::class,
            ClassPackageSchedule::class);
    }
}
