<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Open the CSV file from the database/csv directory (adjust path as needed)
        $csvFile = database_path('csv/users.csv'); // place your CSV here

        if (!file_exists($csvFile) || !is_readable($csvFile)) {
            $this->command->error("CSV file not found or not readable at path: {$csvFile}");
            return;
        }

        // Open file and read
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = null;
            $user_data = [];
            
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row; // first row as header
                    continue;
                }

                $data = array_combine($header, $row);

                // Create user record - adjust keys to your CSV columns and User fillable fields
                $user_data[] = [
                    'name' => $data['name'] ?? null,
                    'email' => $data['email'] ?? null,
                    'mobile_no' => $data['mobile_no'] ?? null,
                    'password' => Hash::make( $data['password'] ) ?? null,
                    'is_admin' => 0,
                    'is_coach' => 0,
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ];


                if ( count( $user_data ) === 100) {
                    DB::table('users')->insert($user_data);
                    $user_data = [];    
                }
            }

            // Insert remaining records if any
            if (count($user_data) > 0) {
                DB::table('users')->insert($user_data);
            }       
            fclose($handle);
        }
    }
}
