<?php

namespace Database\Seeders;

use App\Models\ClassLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassLocation::create(['name'=>'Plaza Bender Velodrome']);
        ClassLocation::create(['name'=>'JIRTA']);
    }
}
