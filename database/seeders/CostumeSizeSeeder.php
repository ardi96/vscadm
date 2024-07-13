<?php

namespace Database\Seeders;

use App\Models\CostumeSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostumeSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CostumeSize::create(['name' => 'Anak XS']);
        CostumeSize::create(['name' => 'Anak S']);
        CostumeSize::create(['name' => 'Anak M']);
        CostumeSize::create(['name' => 'Anak L']);
        CostumeSize::create(['name' => 'Anak XL']);
        CostumeSize::create(['name' => 'Dewasa S']);
        CostumeSize::create(['name' => 'Dewasa M']);
        CostumeSize::create(['name' => 'Dewasa L']);
        CostumeSize::create(['name' => 'Lainnya']);
    }
}
