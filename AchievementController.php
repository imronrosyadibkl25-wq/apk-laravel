<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class AchievementController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();

        // Get completed tasks
        $completedTasks = Task::where('user_id', $userId)->where('is_completed', true)->get();
        $totalCompleted = $completedTasks->count();

        // Calculate specific stats
        $highCount = $completedTasks->where('priority', 'high')->count();
        
        $onTimeCount = $completedTasks->filter(function ($task) {
            return $task->updated_at->startOfDay()->lte($task->deadline->startOfDay());
        })->count();

        $earlyCount = $completedTasks->filter(function ($task) {
            return $task->updated_at->startOfDay()->diffInDays($task->deadline->startOfDay(), false) >= 3;
        })->count();

        $nightCount = $completedTasks->filter(function ($task) {
            $hour = $task->updated_at->hour;
            return $hour >= 22 || $hour < 4;
        })->count();

        $maxSubjectCount = $completedTasks->groupBy('subject')->map(function ($group) {
            return $group->count();
        })->max() ?? 0;

        $overdueCount = $completedTasks->filter(function ($task) {
            return $task->updated_at->startOfDay()->gt($task->deadline->startOfDay());
        })->count();

        // Badges definition
        $badges = [
            [
                'id' => 'first_step',
                'title' => 'Langkah Pertama',
                'description' => 'Selesaikan minimal 1 tugas.',
                'icon' => '🚀',
                'unlocked' => $totalCompleted >= 1,
                'current' => $totalCompleted,
                'target' => 1,
                'color' => 'var(--yellow)',
            ],
            [
                'id' => 'high_ambition',
                'title' => 'Ambisi Tinggi',
                'description' => 'Selesaikan 3 tugas dengan prioritas tinggi.',
                'icon' => '🔥',
                'unlocked' => $highCount >= 3,
                'current' => $highCount,
                'target' => 3,
                'color' => 'var(--pink)',
            ],
            [
                'id' => 'time_master',
                'title' => 'Pengendali Waktu',
                'description' => 'Selesaikan 3 tugas tepat waktu atau sebelum deadline.',
                'icon' => '⚡',
                'unlocked' => $onTimeCount >= 3,
                'current' => $onTimeCount,
                'target' => 3,
                'color' => 'var(--blue)',
            ],
            [
                'id' => 'early_bird',
                'title' => 'Early Bird',
                'description' => 'Selesaikan 1 tugas minimal 3 hari sebelum deadline.',
                'icon' => '🌅',
                'unlocked' => $earlyCount >= 1,
                'current' => $earlyCount,
                'target' => 1,
                'color' => 'var(--orange)',
            ],
            [
                'id' => 'night_owl',
                'title' => 'Burung Hantu',
                'description' => 'Selesaikan 1 tugas pada larut malam (22:00 - 04:00).',
                'icon' => '🦉',
                'unlocked' => $nightCount >= 1,
                'current' => $nightCount,
                'target' => 1,
                'color' => 'var(--purple)',
            ],
            [
                'id' => 'subject_specialist',
                'title' => 'Spesialis Subjek',
                'description' => 'Selesaikan 5 tugas dari subjek yang sama.',
                'icon' => '🏆',
                'unlocked' => $maxSubjectCount >= 5,
                'current' => $maxSubjectCount,
                'target' => 5,
                'color' => 'var(--green)',
            ],
            [
                'id' => 'overdue_vanquisher',
                'title' => 'Pemberantas Overdue',
                'description' => 'Selesaikan 1 tugas yang telah melewati deadline.',
                'icon' => '🧹',
                'unlocked' => $overdueCount >= 1,
                'current' => $overdueCount,
                'target' => 1,
                'color' => 'var(--red)',
            ],
        ];

        $unlockedCount = collect($badges)->where('unlocked', true)->count();
        $totalBadges = count($badges);

        return view('achievements', compact('badges', 'unlockedCount', 'totalBadges', 'user'));
    }
}
