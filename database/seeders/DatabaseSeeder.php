<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $doctor = User::firstOrCreate([
            'email' => 'doctor@example.com',
        ], [
            'name' => 'Dr. Smith',
            'role' => 'doctor',
            'password' => bcrypt('password'),
        ]);

        if (!$doctor->doctorProfile) {
            $doctor->doctorProfile()->create([
                'specialty' => 'Cardiologist',
                'experience' => 10,
                'bio' => 'Experienced cardiologist.',
                'hourly_rate' => 150.00,
            ]);

            // Create date-specific availabilities and auto-generate time slots
            $days = [
                ['available_date' => now()->addDays(1)->toDateString(), 'start_time' => '09:00:00', 'end_time' => '13:00:00', 'slot_duration' => 30],
                ['available_date' => now()->addDays(3)->toDateString(), 'start_time' => '12:00:00', 'end_time' => '16:00:00', 'slot_duration' => 30],
                ['available_date' => now()->addDays(5)->toDateString(), 'start_time' => '10:00:00', 'end_time' => '14:00:00', 'slot_duration' => 30],
            ];

            foreach ($days as $day) {
                $availability = $doctor->doctorAvailabilities()->create($day);
                $availability->generateSlots();
            }
        }

        $doctor2 = User::firstOrCreate([
            'email' => 'dr.chen@example.com',
        ], [
            'name' => 'Dr. Emily Chen',
            'role' => 'doctor',
            'password' => bcrypt('password'),
        ]);

        if (!$doctor2->doctorProfile) {
            $doctor2->doctorProfile()->create([
                'specialty' => 'Dermatologist',
                'experience' => 8,
                'bio' => 'Board-certified dermatologist specializing in medical and cosmetic skincare.',
                'hourly_rate' => 130.00,
            ]);

            $days = [
                ['available_date' => now()->addDays(2)->toDateString(), 'start_time' => '11:00:00', 'end_time' => '15:00:00', 'slot_duration' => 30],
                ['available_date' => now()->addDays(4)->toDateString(), 'start_time' => '09:00:00', 'end_time' => '13:00:00', 'slot_duration' => 30],
            ];

            foreach ($days as $day) {
                $availability = $doctor2->doctorAvailabilities()->create($day);
                $availability->generateSlots();
            }
        }

        $doctor3 = User::firstOrCreate([
            'email' => 'dr.marcus@example.com',
        ], [
            'name' => 'Dr. Marcus Johnson',
            'role' => 'doctor',
            'password' => bcrypt('password'),
        ]);

        if (!$doctor3->doctorProfile) {
            $doctor3->doctorProfile()->create([
                'specialty' => 'Pediatrician',
                'experience' => 15,
                'bio' => 'Dedicated pediatrician with a focus on preventive care and child development.',
                'hourly_rate' => 120.00,
            ]);

            $days = [
                ['available_date' => now()->addDays(1)->toDateString(), 'start_time' => '08:00:00', 'end_time' => '12:00:00', 'slot_duration' => 30],
                ['available_date' => now()->addDays(3)->toDateString(), 'start_time' => '08:00:00', 'end_time' => '12:00:00', 'slot_duration' => 30],
                ['available_date' => now()->addDays(5)->toDateString(), 'start_time' => '08:00:00', 'end_time' => '12:00:00', 'slot_duration' => 30],
            ];

            foreach ($days as $day) {
                $availability = $doctor3->doctorAvailabilities()->create($day);
                $availability->generateSlots();
            }
        }

        $patient = User::firstOrCreate([
            'email' => 'patient@example.com',
        ], [
            'name' => 'Jane Doe',
            'role' => 'patient',
            'password' => bcrypt('password'),
        ]);
    }
}
