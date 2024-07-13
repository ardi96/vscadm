<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassSchedule::create(['name'=>'Minggu Pagi Velodrome','location_id'=>1,'schedule_day'=>'Minggu','schedule_start_time'=>'08:00:00']);
        ClassSchedule::create(['name'=>'Minggu Sore Velodrome','location_id'=>1,'schedule_day'=>'Minggu','schedule_start_time'=>'16:00:00']);
        ClassSchedule::create(['name'=>'Jumat Sore JIRTA','location_id'=>2,'schedule_day'=>'Jumat','schedule_start_time'=>'16:00:00']);
    }
}
