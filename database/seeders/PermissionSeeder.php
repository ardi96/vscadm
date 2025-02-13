<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Member 
        Permission::create(['name' => 'create member']);
        Permission::create(['name' => 'view member']);
        Permission::create(['name' => 'edit member']);
        Permission::create(['name' => 'delete member']);
        Permission::create(['name' => 'approve member']);

        // Invoice
        Permission::create(['name' => 'create invoice']);
        Permission::create(['name' => 'view invoice']);
        Permission::create(['name' => 'edit invoice']);
        Permission::create(['name' => 'delete invoice']);
        Permission::create(['name' => 'approve invoice']);

        // Payment
        Permission::create(['name' => 'create payment']);
        Permission::create(['name' => 'view payment']);
        Permission::create(['name' => 'edit payment']);
        Permission::create(['name' => 'delete payment']);
        Permission::create(['name' => 'approve payment']);

        // Lokasi
        Permission::create(['name' => 'create lokasi']);
        Permission::create(['name' => 'view lokasi']);
        Permission::create(['name' => 'edit lokasi']);
        Permission::create(['name' => 'delete lokasi']);

        // Jadwal
        Permission::create(['name' => 'create jadwal']);
        Permission::create(['name' => 'view jadwal']);
        Permission::create(['name' => 'edit jadwal']);
        Permission::create(['name' => 'delete jadwal']);

        // Paket
        Permission::create(['name' => 'create paket']);
        Permission::create(['name' => 'view paket']);
        Permission::create(['name' => 'edit paket']);
        Permission::create(['name' => 'delete paket']);

        // Grade
        Permission::create(['name' => 'create grade']);
        Permission::create(['name' => 'view grade']);
        Permission::create(['name' => 'edit grade']);
        Permission::create(['name' => 'delete grade']);

        // Kelas
        Permission::create(['name' => 'create kelas']);
        Permission::create(['name' => 'view kelas']);
        Permission::create(['name' => 'edit kelas']);
        Permission::create(['name' => 'delete kelas']);

        // Absensi
        Permission::create(['name' => 'create absensi']);
        Permission::create(['name' => 'view absensi']);
        Permission::create(['name' => 'edit absensi']);
        Permission::create(['name' => 'delete absensi']);

        // Grading
        Permission::create(['name' => 'create grading']);
        Permission::create(['name' => 'view grading']);
        Permission::create(['name' => 'edit grading']);
        Permission::create(['name' => 'delete grading']);
        Permission::create(['name' => 'approve grading']);

        // Marketing Source
        Permission::create(['name' => 'create marketing source']);
        Permission::create(['name' => 'view marketing source']);
        Permission::create(['name' => 'edit marketing source']);
        Permission::create(['name' => 'delete marketing source']);

        // Costume Size
        Permission::create(['name' => 'create costume size']);
        Permission::create(['name' => 'view costume size']);
        Permission::create(['name' => 'edit costume size']);
        Permission::create(['name' => 'delete costume size']);

        // Holiday
        Permission::create(['name' => 'create holiday']);
        Permission::create(['name' => 'view holiday']);
        Permission::create(['name' => 'edit holiday']);
        Permission::create(['name' => 'delete holiday']);

        // General Info
        Permission::create(['name' => 'create general info']);
        Permission::create(['name' => 'view general info']);
        Permission::create(['name' => 'edit general info']);
        Permission::create(['name' => 'delete general info']);

        // User
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        // Role
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        // Permission
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);

        $admin = Role::create(['name' => 'admin']);
        
        Role::create(['name' => 'finance']);
        Role::create(['name' => 'coach']);
        Role::create(['name' => 'coach-lead']);

        $admin->givePermissionTo(['view user','edit user','create user','delete user','create role','view role','edit role','delete role']);

        $user = User::where('email','noreply@veins-skatingclub.com')->first();

        $user->assignRole('admin');
        
    }
}
