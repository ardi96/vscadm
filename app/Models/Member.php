<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    protected $casts = [
        'schedules' => 'array',
    ];

    public function kelas() : BelongsTo
    {
        return $this->belongsTo(Kelas::class,'kelas_id');
    }

    public function gradings()  : HasMany
    {
        return $this->hasMany(Grading::class,'member_id');
    }

    public function grade() : BelongsTo
    {
        return $this->belongsTo(Grade::class,'grade_id');
    }

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

    public function parent() : BelongsTo
    {
        return $this->belongsTo(User::class,'parent_id');
    }   

    public function schedules() : BelongsToMany
    {
        return $this->belongsToMany(ClassSchedule::class, MemberSchedule::class);
    }

    protected function getCurrentMarkAttribute()
    {
        $mark = 0; 

        $grading = $this->gradings()->where('grade_id', $this->grade_id)->first();

        if ( $grading != null )
        {
            $mark = $grading->marks;
        }

        return $mark;
    }

    protected function getMarkAttribute() 
    {
        $mark = 0; 

        $grading = $this->gradings()->get()->last();

        if ( $grading != null )
        {
            $mark = $grading->marks;
        }

        return $mark;
    }

    protected function getLastGradingIdAttribute()
    {
        $id = null;

        $grading = $this->gradings()->get()->last();

        if ( $grading != null )
        {
            $id = $grading->id;
        }

        return $id; 

    }
}
