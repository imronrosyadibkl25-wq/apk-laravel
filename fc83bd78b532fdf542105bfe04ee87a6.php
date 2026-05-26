<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <span class="tag-decoration">📊 Dashboard</span>
        <h1 class="page-title">Halo, <?php echo e(Auth::user()->name); ?>! 👋</h1>
        <p class="page-subtitle">Yuk, pantau progres tugas kamu hari ini.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card yellow animate-bounce-in">
            <div class="stat-icon">📋</div>
            <div class="stat-number"><?php echo e($totalTasks); ?></div>
            <div class="stat-label">Total Tugas</div>
        </div>
        <div class="stat-card green animate-bounce-in">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?php echo e($completedTasks); ?></div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card pink animate-bounce-in">
            <div class="stat-icon">⏳</div>
            <div class="stat-number"><?php echo e($pendingTasks); ?></div>
            <div class="stat-label">Belum Selesai</div>
        </div>
        <div class="stat-card blue animate-bounce-in">
            <div class="stat-icon">🎯</div>
            <div class="stat-number"><?php echo e($progressPercent); ?>%</div>
            <div class="stat-label">Progress</div>
        </div>
    </div>

    <div class="progress-section">
        <div class="progress-container">
            <div class="progress-header">
                <span class="progress-title">📈 Progress Keseluruhan</span>
                <span class="progress-percent"><?php echo e($progressPercent); ?>%</span>
            </div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" style="width: <?php echo e($progressPercent); ?>%;"></div>
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
                    <?php if(Auth::user()->level >= 15): ?> 🏆 <?php elseif(Auth::user()->level >= 10): ?> ⚡ <?php elseif(Auth::user()->level >= 5): ?> 📚 <?php else: ?> 🎓 <?php endif; ?>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo e(Auth::user()->name); ?></h3>
                    <div style="font-size: 0.95rem; font-weight: 700; color: var(--pink); margin-bottom: 0.8rem;"><?php echo e(Auth::user()->levelTitle()); ?></div>
                    
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.3rem;">
                        <span>Level <?php echo e(Auth::user()->level); ?></span>
                        <span><?php echo e(Auth::user()->xp); ?> / <?php echo e(Auth::user()->xpForNextLevel()); ?> XP</span>
                    </div>
                    <div class="progress-bar-track" style="height: 15px; border-radius: 8px; background: #e0e0e0;">
                        <div class="progress-bar-fill" style="width: <?php echo e(Auth::user()->xpPercentage()); ?>%; border-radius: 8px; background: linear-gradient(90deg, var(--green), var(--purple));"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Misi Harian -->
        <div class="card animate-fade-in" style="background: var(--white); border-color: var(--black);">
            <h2 class="section-title" style="margin-top: 0;"><i class='bx bxs-bolt-circle' style="color: var(--orange);"></i> Misi Harian (Daily Quests)</h2>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1.2rem;">
                <?php $__currentLoopData = $dailyQuests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display: flex; align-items: center; justify-content: space-between; border: 2px solid var(--black); border-radius: var(--radius); padding: 0.6rem 0.8rem; background: <?php echo e($quest['completed'] ? 'rgba(6, 214, 160, 0.12)' : 'var(--white)'); ?>; opacity: <?php echo e($quest['completed'] ? '0.85' : '1'); ?>; transition: all 0.2s ease;">
                    <div style="display: flex; align-items: center; gap: 0.8rem; min-width: 0; flex: 1;">
                        <span style="font-size: 1.3rem; flex-shrink: 0;"><?php echo e($quest['icon']); ?></span>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 700; text-decoration: <?php echo e($quest['completed'] ? 'line-through' : 'none'); ?>; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo e($quest['title']); ?>"><?php echo e($quest['title']); ?></div>
                            <span style="background: var(--yellow); border: 1.5px solid var(--black); border-radius: 4px; padding: 0.02rem 0.25rem; font-size: 0.7rem; font-weight: 700; display: inline-block; margin-top: 0.1rem; color: var(--black);">+<?php echo e($quest['xp']); ?> XP</span>
                        </div>
                    </div>
                    <div style="flex-shrink: 0; margin-left: 0.5rem;">
                        <?php if($quest['completed']): ?>
                        <span style="background: var(--green); border: 1.5px solid var(--black); border-radius: 4px; padding: 0.15rem 0.4rem; font-size: 0.75rem; font-weight: 800; color: var(--black);">Selesai</span>
                        <?php else: ?>
                        <span style="background: #e0e0e0; border: 1.5px solid var(--black); border-radius: 4px; padding: 0.15rem 0.4rem; font-size: 0.75rem; font-weight: 800; color: #666;">Aktif</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <h2 class="section-title"><i class='bx bxs-flag-alt'></i> Prioritas Tugas Pending</h2>
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card" style="background: var(--red); color: var(--white);">
                <div class="stat-icon">🔴</div>
                <div class="stat-number"><?php echo e($highPriority); ?></div>
                <div class="stat-label">High Priority</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon">🟡</div>
                <div class="stat-number"><?php echo e($mediumPriority); ?></div>
                <div class="stat-label">Medium Priority</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">🟢</div>
                <div class="stat-number"><?php echo e($lowPriority); ?></div>
                <div class="stat-label">Low Priority</div>
            </div>
        </div>
    </div>

    <?php if($overdueTasks->count() > 0): ?>
    <div class="overdue-section animate-slide-in">
        <h2 class="section-title" style="color: var(--red);">
            <i class='bx bxs-error-circle'></i> Tugas Terlambat! (<?php echo e($overdueTasks->count()); ?>)
        </h2>
        <div class="task-list">
            <?php $__currentLoopData = $overdueTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="task-item overdue">
                <div class="task-content">
                    <div class="task-title"><?php echo e($task->title); ?></div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject"><?php echo e($task->subject); ?></span>
                        <span class="task-badge badge-deadline overdue">
                            <i class='bx bx-calendar'></i> <?php echo e($task->deadline->format('d M Y')); ?>

                        </span>
                        <span class="task-badge badge-priority-<?php echo e($task->priority); ?>"><?php echo e(ucfirst($task->priority)); ?></span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-green btn-sm"><i class='bx bx-check'></i> Selesai</button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($nearDeadlineTasks->count() > 0): ?>
    <div class="warning-section animate-slide-in">
        <h2 class="section-title" style="color: var(--orange);">
            <i class='bx bxs-time-five'></i> Deadline Segera! (<?php echo e($nearDeadlineTasks->count()); ?>)
        </h2>
        <div class="task-list">
            <?php $__currentLoopData = $nearDeadlineTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="task-item near-deadline">
                <div class="task-content">
                    <div class="task-title"><?php echo e($task->title); ?></div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject"><?php echo e($task->subject); ?></span>
                        <span class="task-badge badge-deadline near">
                            <i class='bx bx-calendar'></i> <?php echo e($task->deadline->format('d M Y')); ?> (<?php echo e($task->deadline->diffForHumans()); ?>)
                        </span>
                        <span class="task-badge badge-priority-<?php echo e($task->priority); ?>"><?php echo e(ucfirst($task->priority)); ?></span>
                    </div>
                </div>
                <div class="task-actions">
                    <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-green btn-sm"><i class='bx bx-check'></i> Selesai</button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 class="section-title" style="margin-bottom:0;">
                <i class='bx bx-history'></i> Tugas Terbaru
            </h2>
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-black btn-sm">Lihat Semua <i class='bx bx-right-arrow-alt'></i></a>
        </div>
        <?php if($recentTasks->count() > 0): ?>
        <div class="task-list">
            <?php $__currentLoopData = $recentTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="task-item <?php echo e($task->is_completed ? 'completed' : ''); ?> <?php echo e($task->deadline_status === 'overdue' ? 'overdue' : ''); ?> <?php echo e($task->deadline_status === 'near' ? 'near-deadline' : ''); ?>">
                <div class="task-content">
                    <div class="task-title"><?php if($task->is_completed): ?> ✅ <?php endif; ?> <?php echo e($task->title); ?></div>
                    <div class="task-meta">
                        <span class="task-badge badge-subject"><?php echo e($task->subject); ?></span>
                        <span class="task-badge badge-deadline <?php echo e($task->deadline_status === 'overdue' ? 'overdue' : ''); ?> <?php echo e($task->deadline_status === 'near' ? 'near' : ''); ?>">
                            <i class='bx bx-calendar'></i> <?php echo e($task->deadline->format('d M Y')); ?>

                        </span>
                        <span class="task-badge badge-priority-<?php echo e($task->priority); ?>"><?php echo e(ucfirst($task->priority)); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class='bx bx-book-open'></i>
            <h3>Belum ada tugas</h3>
            <p>Yuk mulai tambahkan tugas pertamamu!</p>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-yellow btn-lg"><i class='bx bx-plus'></i> Tambah Tugas</a>
        </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-yellow btn-lg"><i class='bx bx-plus-circle'></i> Tambah Tugas Baru</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>