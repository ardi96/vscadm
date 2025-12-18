<?php

namespace App\Models;

use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Dflydev\DotAccessData\DataInterface;
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

    public function getLastGradingPeriodAttribute()
    {
        $period = null;

        $grading = $this->gradings()->get()->last();

        if ( $grading != null )
        {
            $period = date("F", strtotime(date("Y") ."-". $grading->month ."-01"))  .' '. $grading->year;
        }

        return $period;
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

    public function getAvailableSessionDay(?string $from, ?string $to) : int
    {
        $day_map = array('Minggu' => 0, 'Senin' => 1, 'Selasa' => 2,'Rabu' => 3,'Kamis' => 4,'Jumat' => 5,'Sabtu' => 6);

        $available_days = 0;
        
        $schedule_days = [];

        $to_date = new DateTime( $to );
        $from_date = new DateTime ( $from );

        $schedules = $this->schedules()->get();

        foreach( $schedules as $schedule)
        {
            $schedule_day =  $day_map[$schedule->schedule_day];
            
            if ( array_search($schedule_day, $schedule_days) === false )
            {
                $schedule_days[] = $schedule_day;
            }

        }

        $current_date = $from_date;

        while ($current_date <= $to_date)
        {
            $day_num = date_format( $current_date,'w' );

            foreach( $schedule_days as $schedule_day )
            {
                if ( $day_num == $schedule_day )
                {
                    $available_days++;
                }
            }

            $current_date = date_add($current_date, DateInterval::createFromDateString('1 day'));
        }

        return $available_days;
    }

    /**
     * will return the number of holiday 
     * falls on the member schedule
     * from the given date range
     * 
     * when the holiday is marked as carried forward and the member schedule    
     * fall on the holiday date, we increase the count of carried forward holiday
     * but if the member schedule is not on the holiday date, we don't increase the count
     */
    public function getHolidayCount(?string $from, ?string $to) : int
    {
        
        $holiday_count = 0;

        $to_date = new DateTime( $to );

        $from_date = new DateTime ( $from );

        $holidays = Holiday::whereBetween('tanggal',array($from_date,$to_date))->get();

        Log::info('Holidays: ' . $holidays->count());
        
        if ( count($holidays) > 0 )
        {
            foreach( $holidays as $holiday)
            {
                
                if ( $holiday->is_carried_forward == true )
                {
                    $day = new DateTime( $holiday->tanggal );

                    $this->schedules()->get()->each(function($schedule) use ($day, &$holiday_count) {
        
                        $day_map = array('Minggu' => 0, 'Senin' => 1, 'Selasa' => 2,'Rabu' => 3,'Kamis' => 4,'Jumat' => 5,'Sabtu' => 6);

                        $schedule_day = $schedule->schedule_day;

                        $day_num = date_format($day,'w');
                        
                        if ( $day_num == $day_map[$schedule_day] )
                        {
                            $holiday_count++;
                            Log::info('Holiday Count: ' . $holiday_count);
                        }
                    }); 

                }
            }
        }

        return $holiday_count;
    }

    public function getCarriedForwardHoliday(?string $from, ?string $to) : int
    {
        $carried_forward = 0;

        $available_days = $this->getAvailableSessionDay($from, $to);
        $holiday_count = $this->getHolidayCount($from, $to);

        $eligible_sessions = $this->package->session_per_week;
     
        if ( $available_days - $holiday_count < $eligible_sessions )
        {
            $carried_forward = $eligible_sessions - ($available_days - $holiday_count);
        }
        else
        {
            $carried_forward = 0;
        }

        return $carried_forward;
    }

    public function getIsLeaveAttribute() : bool
    {
        $is_leave = false;

        $today = Date::now()->format('Y-m-01');

        $leave = Leave::where('member_id', $this->id)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->where('status', 1) // approved
                ->first();

        if ( $leave != null )
        {
            $is_leave = true;
        }

        return $is_leave;
    }

    public function resignations() : HasMany
    {
        return $this->hasMany(Resignation::class,'member_id');
    }

   public static function formatMemberId(int $state) : string
   {
        return 'VSC' . str_pad($state, 4, '0', STR_PAD_LEFT);
   }

   public function iuranBulananMembers() : HasMany
   {
        return $this->hasMany(IuranBulananMember::class,'member_id');
   }
}
