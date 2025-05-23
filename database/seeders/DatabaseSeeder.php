<?php

namespace Database\Seeders;

use App\Models\ClassLocation;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::create([
        //     'name' => 'Veins Administrator',
        //     'email' => 'noreply@veins-skatingclub.com',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => Date::now(),
        //     'mobile_no' => '08119182677',
        //     'is_admin' => true
        // ]);

        // User::create([
        //     'name' => 'Adhib Veins',
        //     'email' => 'adhib@veins-skatingclub.com',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => Date::now(),
        //     'mobile_no' => '08228898989',
        //     'is_admin' => true
        // ]);

        // User::create([
        //     'name' => 'Ardiansyah Tester',
        //     'email' => 'ardi96@gmail.com',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => Date::now(),
        //     'mobile_no' => '081111111',
        //     'is_admin' => false
        // ]);

        (new CostumeSizeSeeder())->run();

        (new MarketingSourceSeeder())->run();
        
        // (new ClassLocationSeeder())->run();
        
        // (new ClassScheduleSeeder())->run();
        
        // (new ClassPackageSeeder())->run();
    }
}
