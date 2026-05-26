@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <span class="tag-decoration">📊 Dashboard</span>
        <h1 class="page-title">Halo, {{ Auth::user()->name }}! 👋</h1>
        <p class="page-subtitle">Yuk, pantau progres tugas kamu hari ini.</p>
    </div>

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

    <!-- RPG Gamification Section -->
    <div class="rpg-dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Card Karakter RPG -->
        <div class="card animate-fade-in" style="background: var(--light-bg); border-color: var(--black);">
            <h2 class="section-title" style="margin-top: 0;"><i class='bx bxs-user-badge'></i> Profil Karakter RPG</h2>
            <div style="display: flex; gap: 1.5rem; align-items: center; margin-top: 1.5rem;">
                <div style="font-size: 3.5rem; background: var(--yellow); border: var(--border-thick); padding: 0.5rem; border-radius: 50%; width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); flex-shrink: 0;">
                    @if(Auth::user()->level >= 15) 🏆 @elseif(Auth::user()->level >= 10) ⚡ @elseif(Auth::user()->level >= 5) 📚 @else 🎓 @endif
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->name }}</h3>
                    <div style="font-size: 0.95rem; font-weight: 700; color: var(--pink); margin-bottom: 0.8rem;">{{ Auth::user()->levelTitle() }}</div>
                    
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.3rem;">
                        <span>Level {{ Auth::user()->level }}</span>
                        <span>{{ Auth::user()->xp }} / {{ Auth::user()->xpForNextLevel() }} XP</span>
                    </div>
                    <div class="progress-bar-track" style="height: 15px; border-radius: 8px; background: #e0e0e0;">
                        <div class="progress-bar-fill" style="width: {{ Auth::user()->xpPercentage() }}%; border-radius: 8px; background: linear-gradient(90deg, var(--green), var(--purple));"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Misi Harian -->
        <div class="card animate-fade-in" style="background: var(--white); border-color: var(--black);">
            <h2 class="section-title" style="margin-top: 0;"><i class='bx bxs-bolt-circle' style="color: var(--orange);"></i> Misi Harian (Daily Quests)</h2>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1.2rem;">
                @foreach($dailyQuests as $quest)
                <div style="display: flex; align-items: center; justify-content: space-between; border: 2px solid var(--black); border-radius: var(--radius); padding: 0.6rem 0.8rem; background: {{ $quest['completed'] ? 'rgba(6, 214, 160, 0.12)' : 'var(--white)' }}; opacity: {{ $quest['completed'] ? '0.85' : '1' }}; transition: all 0.2s ease;">
                    <div style="display: flex; align-items: center; gap: 0.8rem; min-width: 0; flex: 1;">
                        <span style="font-size: 1.3rem; flex-shrink: 0;">{{ $quest['icon'] }}</span>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 700; text-decoration: {{ $quest['completed'] ? 'line-through' : 'none' }}; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $quest['title'] }}">{{ $quest['title'] }}</div>
                            <span style="background: var(--yellow); border: 1.5px solid var(--black); border-radius: 4px; padding: 0.02rem 0.25rem; font-size: 0.7rem; font-weight: 700; display: inline-block; margin-top: 0.1rem; color: var(--black);">+{{ $quest['xp'] }} XP</span>
                        </div>
                    </div>
                    <div style="flex-shrink: 0; margin-left: 0.5rem;">
                        @if($quest['completed'])
                        <span style="background: var(--green); border: 1.5px solid var(--black); border-radius: 4px; padding: 0.15rem 0.4rem; font-size: 0.75rem; font-weight: 800; color: var(--black);">Selesai</span>
                        @else
                        <span style="background: #e0e0e0; border: 1.5px solid var(--black); border-radius: 4px; padding: 0.15rem 0.4rem; font-size: 0.75rem; font-weight: 800; color: #666;">Aktif</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

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
                        <span class="task-badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-green btn-sm"><i class='bx bx-check'></i> Selesai</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
                            <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }} ({{ $task->deadline->diffForHumans() }})
                        </span>
                        <span class="task-badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-green btn-sm"><i class='bx bx-check'></i> Selesai</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 class="section-title" style="margin-bottom:0;">
                <i class='bx bx-history'></i> Tugas Terbaru
            </h2>
            <a href="{{ route('tasks.index') }}" class="btn btn-black btn-sm">Lihat Semua <i class='bx bx-right-arrow-alt'></i></a>
        </div>
        @if($recentTasks->count() > 0)
        <div class="task-list">
            @foreach($recentTasks as $task)
            <div class="task-item {{ $task->is_completed ? 'completed' : '' }} {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near-deadline' : '' }}">
                <div class="task-content">
                    <div class="task-title">@if($task->is_completed) ✅ @endif {{ $task->title }}</div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject">{{ $task->subject }}</span>
                        <span class="task-badge badge-deadline {{ $task->deadline_status === 'overdue' ? 'overdue' : '' }} {{ $task->deadline_status === 'near' ? 'near' : '' }}">
                            <i class='bx bx-calendar'></i> {{ $task->deadline->format('d M Y') }}
                        </span>
                        <span class="task-badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
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
            <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg"><i class='bx bx-plus'></i> Tambah Tugas</a>
        </div>
        @endif
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('tasks.create') }}" class="btn btn-yellow btn-lg"><i class='bx bx-plus-circle'></i> Tambah Tugas Baru</a>
    </div>
</div>
@endsection
