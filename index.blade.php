@extends('layouts.app')
@section('title', 'Daftar Tugas')

@section('content')
<div class="animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <span class="tag-decoration">📚 Tugas Saya</span>
            <h1 class="page-title">Daftar Tugas</h1>
            <p class="page-subtitle">Kelola semua tugas sekolahmu di sini.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('tasks.calendar') }}" class="btn btn-white btn-lg"><i class='bx bx-calendar'></i> Mode Kalender</a>
            <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg"><i class='bx bx-plus-circle'></i> Tambah Tugas</a>
        </div>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="filter-bar">
        <div class="filter-group">
            <label><i class='bx bx-search'></i> Cari Tugas</label>
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}" style="min-width: 150px; height: 38px; margin-top: 5px;">
        </div>
        <div class="filter-group">
            <label><i class='bx bx-book'></i> Mata Pelajaran</label>
            <select name="subject" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class='bx bx-filter'></i> Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Belum Selesai</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Selesai</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class='bx bx-flag'></i> Prioritas</label>
            <select name="priority" onchange="this.form.submit()">
                <option value="">Semua Prioritas</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🔴 High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
            </select>
        </div>
        @if(request()->hasAny(['search', 'subject', 'status', 'priority']))
            <a href="{{ route('tasks.index') }}" class="btn btn-white btn-sm" style="align-self: flex-end; height: 38px; display: flex; align-items: center;"><i class='bx bx-x'></i> Reset</a>
        @endif
    </form>

    @if($tasks->count() > 0)
    <div class="task-list">
        @foreach($tasks as $task)
        <div class="task-item {{ $task->is_completed ? 'completed' : '' }} {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near-deadline' : '' }}">
            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $task->is_completed ? 'btn-green' : 'btn-white' }} btn-sm" title="{{ $task->is_completed ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                    <i class='bx {{ $task->is_completed ? "bxs-check-circle" : "bx-circle" }}' style="font-size: 1.3rem;"></i>
                </button>
            </form>
            <div class="task-content">
                <div class="task-title">
                    {{ $task->title }}
                    @if($task->deadline_status === 'overdue')
                        <span style="color: var(--red); font-size: 0.8rem;">⚠️ TERLAMBAT</span>
                    @elseif($task->deadline_status === 'near')
                        <span style="color: var(--orange); font-size: 0.8rem;">⏰ SEGERA</span>
                    @endif
                </div>
                @if($task->description)
                <div class="task-description" style="font-size: 0.9rem; color: #555; margin-bottom: 0.5rem; white-space: pre-line;">
                    {{ \Illuminate\Support\Str::limit($task->description, 100) }}
                </div>
                @endif
                <div class="task-meta">
                    <span class="task-badge badge-subject"><i class='bx bx-book-open'></i> {{ $task->subject }}</span>
                    <span class="task-badge badge-deadline {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near' : '' }}">
                        <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }}
                        @if(!$task->is_completed) ({{ $task->deadline->diffForHumans() }}) @endif
                    </span>
                    <span class="task-badge badge-priority-{{ $task->priority }}">
                        @if($task->priority === 'high') 🔴 @elseif($task->priority === 'medium') 🟡 @else 🟢 @endif
                        {{ ucfirst($task->priority) }}
                    </span>
                    @if($task->is_completed)
                        <span class="task-badge" style="background: var(--green);">✅ Selesai</span>
                    @endif
                    @if($task->attachment_path)
                        <a href="{{ Storage::url($task->attachment_path) }}" target="_blank" class="task-badge" style="background: #eee; text-decoration: none; color: inherit;"><i class='bx bx-paperclip'></i> Ada Lampiran</a>
                    @endif
                </div>
            </div>
            <div class="task-actions" style="display: flex; gap: 0.5rem;">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-white btn-sm"><i class='bx bx-edit'></i></a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin hapus tugas ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-red btn-sm"><i class='bx bx-trash'></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align: center; margin-top: 1.5rem; font-weight: 600; color: #888;">Menampilkan {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }} dari {{ $tasks->total() }} tugas</div>
    
    @if ($tasks->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $tasks->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <i class='bx bx-search-alt'></i>
        <h3>Tidak ada tugas ditemukan</h3>
        <p>@if(request()->hasAny(['subject', 'status', 'priority'])) Coba ubah filter pencarian kamu. @else Belum ada tugas. Yuk tambahkan! @endif</p>
        <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg"><i class='bx bx-plus'></i> Tambah Tugas</a>
    </div>
    @endif
</div>
@endsection
