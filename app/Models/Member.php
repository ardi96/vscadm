<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Dflydev\DotAccessData\DataInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'schedules' => 'array',
    // ];


    // public function schedules() : HasMany
    // {
    //     return $this->hasMany(ClassSchedule::class,'class_schedule_id');
    // }

    public function kelas() : BelongsTo
    {
        return $this->belongsTo(Kelas::class,'kelas_id');
    }

    public function absensi() : HasMany
    {
        return $this->hasMany(Absensi::class,'member_id');
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
        return $this->belongsToMany(ClassSchedule::class,'member_schedules');
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

    public function getSessionCount(?string $from, ?string $to) : int
    {
        $session_count = 0;

        $day_map = array('Minggu' => 0, 'Senin' => 1, 'Selasa' => 2,'Rabu' => 3,'Kamis' => 4,'Jumat' => 5,'Sabtu' => 6);
        
        $to_date = new DateTime($to);

        $schedules = $this->schedules()->get();

        foreach( $schedules as $schedule)
        {
            $schedule_day =  $schedule->schedule_day;
            
            $current_date = new DateTime( $from ); 
        
            while ($current_date <= $to_date)
            {
                $day_num = $day_map[$schedule_day];

                if ( $day_num == date_format( $current_date,'w') ){
                    $session_count++;
                }

                $current_date = date_add($current_date, DateInterval::createFromDateString('1 day'));
            }
        }

        return $session_count; 
    }

    public function getAttendanceCount(?string $from, ?string $to) : int
    {
        $attendance_count = 0;

        $to_date = new DateTime( $to );
        $from_date = new DateTime ( $from );

        $attendance_count = Absensi::where('member_id', $this->id)
                ->whereBetween('tanggal',array($from_date,$to_date))
                ->count('hadir');

        return $attendance_count;
    }
}
