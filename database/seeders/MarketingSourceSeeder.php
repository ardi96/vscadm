<?php

namespace Database\Seeders;

use App\Models\MarketingSource;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MarketingSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MarketingSource::create(['name' => 'Melihat kegiatan di Velodrome']);
        MarketingSource::create(['name' => 'Referensi kolega']);
        MarketingSource::create(['name' => 'Sosial Media']);
        MarketingSource::create(['name' => 'Lainnya']);
    }
}
