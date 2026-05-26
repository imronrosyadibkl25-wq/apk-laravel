<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // General Stats
        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        
        // Tasks by Priority
        $priorityData = Task::where('user_id', $userId)
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')->toArray();
            
        // Ensure all priorities exist in the array even if 0
        $priorityData = [
            'high' => $priorityData['high'] ?? 0,
            'medium' => $priorityData['medium'] ?? 0,
            'low' => $priorityData['low'] ?? 0,
        ];

        // Tasks by Subject (Top 7)
        $subjectData = Task::where('user_id', $userId)
            ->select('subject', DB::raw('count(*) as total'))
            ->groupBy('subject')
            ->orderByDesc('total')
            ->limit(7)
            ->pluck('total', 'subject')->toArray();

        // Tasks over the last 30 days (Daily)
        $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay();
        $dailyDataRaw = Task::where('user_id', $userId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')->toArray();
            
        $dailyLabels = [];
        $dailyValues = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::now()->subDays($i)->format('d M');
            $dailyValues[] = $dailyDataRaw[$date] ?? 0;
        }

        // Completion Status Data
        $completionData = [
            'Selesai' => $completedTasks,
            'Belum Selesai' => $pendingTasks
        ];

        return view('statistics', compact(
            'totalTasks', 'completedTasks', 'pendingTasks', 
            'priorityData', 'subjectData', 
            'dailyLabels', 'dailyValues', 'completionData'
        ));
    }
}
