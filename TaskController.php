<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks with filtering.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Task::where('user_id', $userId);

        // Apply filters
        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        // Order: incomplete first, then by deadline (nearest first)
        $tasks = $query->orderBy('is_completed', 'asc')
                       ->orderBy('deadline', 'asc')
                       ->get();

        // Get unique subjects for filter dropdown
        $subjects = Task::where('user_id', $userId)
                        ->select('subject')
                        ->distinct()
                        ->pluck('subject');

        return view('tasks.index', compact('tasks', 'subjects'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        // Predefined subjects list
        $subjects = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'PKN',
            'Seni Budaya',
            'PJOK',
            'Informatika',
            'Agama',
            'Prakarya',
            'Lainnya',
        ];

        return view('tasks.create', compact('subjects'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'deadline' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
        ]);

        Task::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'is_completed' => false,
        ]);

        return redirect()->route('tasks.index')
                         ->with('success', '✅ Tugas berhasil ditambahkan!');
    }

    /**
     * Toggle the completion status of a task.
     */
    public function toggleComplete(Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        $message = $task->is_completed
            ? '🎉 Tugas selesai! Bagus!'
            : '📝 Tugas ditandai belum selesai.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        // Ensure user owns this task
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('success', '🗑️ Tugas berhasil dihapus!');
    }
}
