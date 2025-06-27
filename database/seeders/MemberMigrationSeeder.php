<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MemberMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // Open the CSV file from the database/csv directory (adjust path as needed)
        $csvFile = database_path('csv/members.csv'); // place your CSV here

        if (!file_exists($csvFile) || !is_readable($csvFile)) {
            $this->command->error("CSV file not found or not readable at path: {$csvFile}");
            return;
        }

        // Open file and read
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = null;
            $member_data = [];
            
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row; // first row as header
                    continue;
                }

                $data = array_combine($header, $row);

                if ( $data['date_of_birth'] == '' ) {
                    $data['date_of_birth'] = null; // Handle empty date_of_birth
                }
                // Create user record - adjust keys to your CSV columns and User fillable fields
                $member_data[] = [
                    'name' => $data['name'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'parent_name' => $data['parent_name'] ?? null,
                    'parent_mobile_no' => $data['parent_mobile_no'] ?? null,
                    'class_package_id' => $data['class_package_id'] ?? null,
                    'status' => $data['status'] ?? 'active',
                    'balance' => 0,
                    'parent_id' => $data['parent_id'] ?? null,
                    'grade_id' => $data['grade_id'] == '' ? 0 : $data['grade_id'],
                    'created_at' => Date::now(),
                    'updated_at' => Date::now(),
                ];


                if ( count( $member_data ) === 100) {
                    DB::table('members')->insert($member_data);
                    $member_data = [];    
                }
            }

            // Insert remaining records if any
            if (count($member_data) > 0) {
                DB::table('members')->insert($member_data);
            }       
            fclose($handle);
        }
    }
}
