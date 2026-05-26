<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();
        
        // Reset daily quests if it's a new day
        $today = Carbon::today()->toDateString();
        if ($user->last_quest_reset_date !== $today) {
            $user->last_quest_reset_date = $today;
            $user->completed_quests_today = [];
            $user->save();
        }

        $completedQuestsToday = $user->completed_quests_today ?? [];
        $dailyQuests = [
            [
                'id' => 1,
                'title' => 'Selesaikan 1 tugas hari ini',
                'xp' => 30,
                'completed' => in_array(1, $completedQuestsToday),
                'icon' => '📝'
            ],
            [
                'id' => 2,
                'title' => 'Selesaikan tugas High/Medium hari ini',
                'xp' => 50,
                'completed' => in_array(2, $completedQuestsToday),
                'icon' => '🎯'
            ],
            [
                'id' => 3,
                'title' => 'Tambahkan 1 tugas baru hari ini',
                'xp' => 20,
                'completed' => in_array(3, $completedQuestsToday),
                'icon' => '➕'
            ],
        ];

        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $overdueTasks = Task::where('user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '<', Carbon::today())
            ->orderBy('deadline', 'asc')->get();

        $nearDeadlineTasks = Task::where('user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '>=', Carbon::today())
            ->where('deadline', '<=', Carbon::today()->addDays(3))
            ->orderBy('deadline', 'asc')->get();

        $recentTasks = Task::where('user_id', $userId)
            ->orderBy('created_at', 'desc')->take(5)->get();

        $highPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'high')->count();
        $mediumPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'medium')->count();
        $lowPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'low')->count();

        return view('dashboard', compact(
            'totalTasks', 'completedTasks', 'pendingTasks', 'progressPercent',
            'overdueTasks', 'nearDeadlineTasks', 'recentTasks',
            'highPriority', 'mediumPriority', 'lowPriority', 'dailyQuests'
        ));
    }
}
