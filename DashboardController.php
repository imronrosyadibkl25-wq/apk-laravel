<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with task statistics.
     */
    public function index()
    {
        $userId = auth()->id();

        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Overdue tasks (not completed, past deadline)
        $overdueTasks = Task::where('user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '<', Carbon::today())
            ->orderBy('deadline', 'asc')
            ->get();

        // Near deadline tasks (not completed, within 3 days)
        $nearDeadlineTasks = Task::where('user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '>=', Carbon::today())
            ->where('deadline', '<=', Carbon::today()->addDays(3))
            ->orderBy('deadline', 'asc')
            ->get();

        // Recent tasks (latest 5)
        $recentTasks = Task::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tasks by priority count
        $highPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'high')->count();
        $mediumPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'medium')->count();
        $lowPriority = Task::where('user_id', $userId)->where('is_completed', false)->where('priority', 'low')->count();

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'progressPercent',
            'overdueTasks',
            'nearDeadlineTasks',
            'recentTasks',
            'highPriority',
            'mediumPriority',
            'lowPriority'
        ));
    }
}
