<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun user sesuai dengan yang ada di screenshot
        $user = User::factory()->create([
            'name' => 'Imron Rosyadi',
            'email' => 'imronrosyadibkl25@gmail.com',
            'password' => Hash::make('password123'), // Password default
        ])
        ;

        $user = User::factory()->create([
            'name' => 'malik fahad',
            'email' => 'mlkfhad4@gmail.com',
            'password' => Hash::make('password123'), // Password default
        ])
        ;

        // Buat beberapa contoh tugas untuk user ini
        $tasks = [
            [
                'title' => 'Mengerjakan LKS Matematika Hal 45',
                'subject' => 'Matematika',
                'deadline' => Carbon::now()->addDays(2),
                'priority' => 'high',
                'is_completed' => false,
            ],
            [
                'title' => 'Membuat Esai Bahasa Indonesia',
                'subject' => 'Bahasa Indonesia',
                'deadline' => Carbon::now()->addDays(5),
                'priority' => 'medium',
                'is_completed' => false,
            ],
            [
                'title' => 'Membaca Bab 3 Sejarah',
                'subject' => 'IPS',
                'deadline' => Carbon::now()->subDays(1), // Overdue
                'priority' => 'low',
                'is_completed' => false,
            ],
            [
                'title' => 'Praktek Membuat Website',
                'subject' => 'Informatika',
                'deadline' => Carbon::now()->addDays(1),
                'priority' => 'high',
                'is_completed' => true, // Sudah selesai
            ]
        ];

        foreach ($tasks as $taskData) {
            Task::create(array_merge($taskData, ['user_id' => $user->id]));
        }
    }
}
