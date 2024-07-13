<?php

namespace Database\Seeders;

use App\Models\ClassPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassPackage::create([
            'name'=>'Private 2x Seminggu',
            'description'=> 'Paket private coaching dengan frekuensi 2x seminggu, max 10 peserta',
            'session_per_week' => 2,
            'type' => 'private',
            'price' => 400000,
        ]);
    }
}
