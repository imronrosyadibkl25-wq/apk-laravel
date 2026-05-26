@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="animate-fade-in">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <span class="tag-decoration">📊 Dashboard</span>
        <h1 class="page-title">Halo, {{ Auth::user()->name }}! 👋</h1>
        <p class="page-subtitle">Yuk, pantau progres tugas kamu hari ini.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card yellow animate-bounce-in">
            <div class="stat-icon">📋</div>
            <div class="stat-number">{{ $totalTasks }}</div>
            <div class="stat-label">Total Tugas</div>
        </div>
        <div class="stat-card green animate-bounce-in">
            <div class="stat-icon">✅</div>
            <div class="stat-number">{{ $completedTasks }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card pink animate-bounce-in">
            <div class="stat-icon">⏳</div>
            <div class="stat-number">{{ $pendingTasks }}</div>
            <div class="stat-label">Belum Selesai</div>
        </div>
        <div class="stat-card blue animate-bounce-in">
            <div class="stat-icon">🎯</div>
            <div class="stat-number">{{ $progressPercent }}%</div>
            <div class="stat-label">Progress</div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-section">
        <div class="progress-container">
            <div class="progress-header">
                <span class="progress-title">📈 Progress Keseluruhan</span>
                <span class="progress-percent">{{ $progressPercent }}%</span>
            </div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" style="width: {{ $progressPercent }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Priority Summary -->
    <div style="margin-bottom: 2rem;">
        <h2 class="section-title"><i class='bx bxs-flag-alt'></i> Prioritas Tugas Pending</h2>
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card" style="background: var(--red); color: var(--white);">
                <div class="stat-icon">🔴</div>
                <div class="stat-number">{{ $highPriority }}</div>
                <div class="stat-label">High Priority</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon">🟡</div>
                <div class="stat-number">{{ $mediumPriority }}</div>
                <div class="stat-label">Medium Priority</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">🟢</div>
                <div class="stat-number">{{ $lowPriority }}</div>
                <div class="stat-label">Low Priority</div>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    @if($overdueTasks->count() > 0)
    <div class="overdue-section animate-slide-in">
        <h2 class="section-title" style="color: var(--red);">
            <i class='bx bxs-error-circle'></i> Tugas Terlambat! ({{ $overdueTasks->count() }})
        </h2>
        <div class="task-list">
            @foreach($overdueTasks as $task)
            <div class="task-item overdue">
                <div class="task-content">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject">{{ $task->subject }}</span>
                        <span class="task-badge badge-deadline overdue">
                            <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }}
                        </span>
                        <span class="task-badge badge-priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-green btn-sm">
                            <i class='bx bx-check'></i> Selesai
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Near Deadline Tasks -->
    @if($nearDeadlineTasks->count() > 0)
    <div class="warning-section animate-slide-in">
        <h2 class="section-title" style="color: var(--orange);">
            <i class='bx bxs-time-five'></i> Deadline Segera! ({{ $nearDeadlineTasks->count() }})
        </h2>
        <div class="task-list">
            @foreach($nearDeadlineTasks as $task)
            <div class="task-item near-deadline">
                <div class="task-content">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject">{{ $task->subject }}</span>
                        <span class="task-badge badge-deadline near">
                            <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }}
                            ({{ $task->deadline->diffForHumans() }})
                        </span>
                        <span class="task-badge badge-priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-green btn-sm">
                            <i class='bx bx-check'></i> Selesai
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Tasks -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 class="section-title" style="margin-bottom:0;">
                <i class='bx bx-history'></i> Tugas Terbaru
            </h2>
            <a href="{{ route('tasks.index') }}" class="btn btn-black btn-sm">
                Lihat Semua <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>

        @if($recentTasks->count() > 0)
        <div class="task-list">
            @foreach($recentTasks as $task)
            <div class="task-item {{ $task->is_completed ? 'completed' : '' }} {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near-deadline' : '' }}">
                <div class="task-content">
                    <div class="task-title">
                        @if($task->is_completed) ✅ @endif
                        {{ $task->title }}
                    </div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject">{{ $task->subject }}</span>
                        <span class="task-badge badge-deadline {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near' : '' }}">
                            <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }}
                        </span>
                        <span class="task-badge badge-priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class='bx bx-book-open'></i>
            <h3>Belum ada tugas</h3>
            <p>Yuk mulai tambahkan tugas pertamamu!</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg">
                <i class='bx bx-plus'></i> Tambah Tugas
            </a>
        </div>
        @endif
    </div>

    <!-- Quick Add Button -->
    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg">
            <i class='bx bx-plus-circle'></i> Tambah Tugas Baru
        </a>
    </div>
</div>
@endsection
