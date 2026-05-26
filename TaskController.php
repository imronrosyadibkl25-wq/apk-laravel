<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = Task::where('user_id', $userId);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('subject')) {
            $query->bySubject($request->subject);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        $tasks = $query->orderBy('is_completed', 'asc')
                       ->orderBy('deadline', 'asc')->paginate(10)->withQueryString();

        $subjects = Task::where('user_id', $userId)
                        ->select('subject')->distinct()->pluck('subject');

        return view('tasks.index', compact('tasks', 'subjects'));
    }

    public function create()
    {
        $subjects = [
            // Mata Pelajaran Umum
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pendidikan Agama', 'PKN',
            'Sejarah', 'PJOK', 'Seni Budaya', 'Bimbingan Konseling (BK)',
            // Peminatan / Jurusan / Kejuruan
            'IPA (Fisika/Kimia/Biologi)', 'IPS (Ekonomi/Geografi/Sosiologi)', 
            'Informatika / Simkomdig', 'PKKWU (Kewirausahaan)',
            // Khusus RPL / IT
            'Basis Data', 'PBG (Perangkat Bergerak)', 'PBO (Berorientasi Objek)', 
            'Pemrograman Web', 'Desain Grafis', 'Matpil (Mata Pelajaran Pilihan)',
            'Lainnya',
        ];
        return view('tasks.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // max 10MB
            'subject' => 'required|string|max:100',
            'deadline' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $task = Task::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'attachment_path' => $attachmentPath,
            'subject' => $validated['subject'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'is_completed' => false,
        ]);

        $user = auth()->user();
        $unlockedQuests = $user->checkDailyQuests('create_task');
        
        $successMessage = '✅ Tugas berhasil ditambahkan!';
        if (!empty($unlockedQuests)) {
            foreach ($unlockedQuests as $quest) {
                $successMessage .= "\n🎉 Quest Selesai: " . $quest['title'] . " (+" . $quest['xp'] . " XP)!";
                if ($quest['level_up']) {
                    $successMessage .= "\n⬆️ LEVEL UP! Sekarang kamu Level " . $user->level . "!";
                }
            }
        }

        return redirect()->route('tasks.index')->with('success', $successMessage);
    }

    public function toggleComplete(Task $task)
    {
        if ($task->user_id !== auth()->id()) { abort(403); }
        $task->update(['is_completed' => !$task->is_completed]);
        
        $user = auth()->user();
        $xpEarned = 0;
        if ($task->priority === 'high') {
            $xpEarned += 80;
        } elseif ($task->priority === 'medium') {
            $xpEarned += 40;
        } else {
            $xpEarned += 20;
        }
        
        $isOnTime = $task->deadline->startOfDay()->gte(\Carbon\Carbon::now()->startOfDay());
        if ($isOnTime) {
            $xpEarned += 20; // On-time bonus
        }

        $message = '';
        if ($task->is_completed) {
            $levelUp = $user->addXp($xpEarned);
            $message = "🎉 Tugas selesai! Bagus! (+" . $xpEarned . " XP" . ($isOnTime ? ' + Bonus Tepat Waktu' : '') . ")";
            if ($levelUp) {
                $message .= "\n⬆️ LEVEL UP! Sekarang kamu Level " . $user->level . "!";
            }
            
            // Check Daily Quests
            $unlockedQuests = $user->checkDailyQuests('complete_task', ['priority' => $task->priority]);
            if (!empty($unlockedQuests)) {
                foreach ($unlockedQuests as $quest) {
                    $message .= "\n🎯 Quest Selesai: " . $quest['title'] . " (+" . $quest['xp'] . " XP)!";
                    if ($quest['level_up']) {
                        $message .= "\n⬆️ LEVEL UP! Sekarang kamu Level " . $user->level . "!";
                    }
                }
            }
        } else {
            $user->deductXp($xpEarned);
            $message = "📝 Tugas ditandai belum selesai. (-" . $xpEarned . " XP)";
        }
        
        return redirect()->back()->with('success', $message);
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) { abort(403); }
        $subjects = [
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pendidikan Agama', 'PKN',
            'Sejarah', 'PJOK', 'Seni Budaya', 'Bimbingan Konseling (BK)',
            'IPA (Fisika/Kimia/Biologi)', 'IPS (Ekonomi/Geografi/Sosiologi)', 
            'Informatika / Simkomdig', 'PKKWU (Kewirausahaan)',
            'Basis Data', 'PBG (Perangkat Bergerak)', 'PBO (Berorientasi Objek)', 
            'Pemrograman Web', 'Desain Grafis', 'Matpil (Mata Pelajaran Pilihan)',
            'Lainnya',
        ];
        return view('tasks.edit', compact('task', 'subjects'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // max 10MB
            'remove_attachment' => 'nullable|boolean',
            'subject' => 'required|string|max:100',
            'deadline' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $attachmentPath = $task->attachment_path;

        if ($request->has('remove_attachment') && $request->remove_attachment) {
            if ($task->attachment_path) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $attachmentPath = null;
        }

        if ($request->hasFile('attachment')) {
            if ($task->attachment_path) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'subject' => $validated['subject'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('tasks.index')->with('success', '✏️ Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) { abort(403); }
        if ($task->attachment_path) {
            Storage::disk('public')->delete($task->attachment_path);
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success', '🗑️ Tugas berhasil dihapus!');
    }

    public function calendar()
    {
        $userId = auth()->id();
        $tasks = Task::where('user_id', $userId)->get();
        return view('tasks.calendar', compact('tasks'));
    }
}
