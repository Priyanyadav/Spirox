<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->delete();

        Admin::create([
            'name' => 'Admin',
            'mobile' => '1234567890',
            'email' => 'admin@gmail.com',
            'role' => 'Admin',
            'assigned_area' => 'Vastral',
            'joining_date' => '2026-02-12',
            'status' => '0',
            'password' => '$2y$10$WbgC33G0U8OCol8Rh9iP1.075Ue2v2gFMq7BRKdfGc2T/t2nSFwNa', // Admin@123
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
