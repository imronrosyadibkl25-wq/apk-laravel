<?php $__env->startSection('title', 'Daftar Tugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <span class="tag-decoration">📚 Tugas Saya</span>
            <h1 class="page-title">Daftar Tugas</h1>
            <p class="page-subtitle">Kelola semua tugas sekolahmu di sini.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?php echo e(route('tasks.calendar')); ?>" class="btn btn-white btn-lg"><i class='bx bx-calendar'></i> Mode Kalender</a>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-yellow btn-lg"><i class='bx bx-plus-circle'></i> Tambah Tugas</a>
        </div>
    </div>

    <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="filter-bar">
        <div class="filter-group">
            <label><i class='bx bx-search'></i> Cari Tugas</label>
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="<?php echo e(request('search')); ?>" style="min-width: 150px; height: 38px; margin-top: 5px;">
        </div>
        <div class="filter-group">
            <label><i class='bx bx-book'></i> Mata Pelajaran</label>
            <select name="subject" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($subject); ?>" <?php echo e(request('subject') == $subject ? 'selected' : ''); ?>><?php echo e($subject); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="filter-group">
            <label><i class='bx bx-filter'></i> Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>⏳ Belum Selesai</option>
                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>✅ Selesai</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class='bx bx-flag'></i> Prioritas</label>
            <select name="priority" onchange="this.form.submit()">
                <option value="">Semua Prioritas</option>
                <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>🔴 High</option>
                <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>🟡 Medium</option>
                <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>🟢 Low</option>
            </select>
        </div>
        <?php if(request()->hasAny(['search', 'subject', 'status', 'priority'])): ?>
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-white btn-sm" style="align-self: flex-end; height: 38px; display: flex; align-items: center;"><i class='bx bx-x'></i> Reset</a>
        <?php endif; ?>
    </form>

    <?php if($tasks->count() > 0): ?>
    <div class="task-list">
        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="task-item <?php echo e($task->is_completed ? 'completed' : ''); ?> <?php echo e($task->deadline_status === 'overdue' ? 'overdue' : ''); ?> <?php echo e($task->deadline_status === 'near' ? 'near-deadline' : ''); ?>">
            <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button type="submit" class="btn <?php echo e($task->is_completed ? 'btn-green' : 'btn-white'); ?> btn-sm" title="<?php echo e($task->is_completed ? 'Tandai belum selesai' : 'Tandai selesai'); ?>">
                    <i class='bx <?php echo e($task->is_completed ? "bxs-check-circle" : "bx-circle"); ?>' style="font-size: 1.3rem;"></i>
                </button>
            </form>
            <div class="task-content">
                <div class="task-title">
                    <?php echo e($task->title); ?>

                    <?php if($task->deadline_status === 'overdue'): ?>
                        <span style="color: var(--red); font-size: 0.8rem;">⚠️ TERLAMBAT</span>
                    <?php elseif($task->deadline_status === 'near'): ?>
                        <span style="color: var(--orange); font-size: 0.8rem;">⏰ SEGERA</span>
                    <?php endif; ?>
                </div>
                <?php if($task->description): ?>
                <div class="task-description" style="font-size: 0.9rem; color: #555; margin-bottom: 0.5rem; white-space: pre-line;">
                    <?php echo e(\Illuminate\Support\Str::limit($task->description, 100)); ?>

                </div>
                <?php endif; ?>
                <div class="task-meta">
                    <span class="task-badge badge-subject"><i class='bx bx-book-open'></i> <?php echo e($task->subject); ?></span>
                    <span class="task-badge badge-deadline <?php echo e($task->deadline_status === 'overdue' ? 'overdue' : ''); ?> <?php echo e($task->deadline_status === 'near' ? 'near' : ''); ?>">
                        <i class='bx bx-calendar'></i> <?php echo e($task->deadline->format('d M Y')); ?>

                        <?php if(!$task->is_completed): ?> (<?php echo e($task->deadline->diffForHumans()); ?>) <?php endif; ?>
                    </span>
                    <span class="task-badge badge-priority-<?php echo e($task->priority); ?>">
                        <?php if($task->priority === 'high'): ?> 🔴 <?php elseif($task->priority === 'medium'): ?> 🟡 <?php else: ?> 🟢 <?php endif; ?>
                        <?php echo e(ucfirst($task->priority)); ?>

                    </span>
                    <?php if($task->is_completed): ?>
                        <span class="task-badge" style="background: var(--green);">✅ Selesai</span>
                    <?php endif; ?>
                    <?php if($task->attachment_path): ?>
                        <a href="<?php echo e(Storage::url($task->attachment_path)); ?>" target="_blank" class="task-badge" style="background: #eee; text-decoration: none; color: inherit;"><i class='bx bx-paperclip'></i> Ada Lampiran</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="task-actions" style="display: flex; gap: 0.5rem;">
                <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-white btn-sm"><i class='bx bx-edit'></i></a>
                <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" onsubmit="return confirm('Yakin hapus tugas ini?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-red btn-sm"><i class='bx bx-trash'></i></button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div style="text-align: center; margin-top: 1.5rem; font-weight: 600; color: #888;">Menampilkan <?php echo e($tasks->firstItem()); ?> - <?php echo e($tasks->lastItem()); ?> dari <?php echo e($tasks->total()); ?> tugas</div>
    
    <?php if($tasks->hasPages()): ?>
    <div style="margin-top: 1.5rem;">
        <?php echo e($tasks->links()); ?>

    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="empty-state">
        <i class='bx bx-search-alt'></i>
        <h3>Tidak ada tugas ditemukan</h3>
        <p><?php if(request()->hasAny(['subject', 'status', 'priority'])): ?> Coba ubah filter pencarian kamu. <?php else: ?> Belum ada tugas. Yuk tambahkan! <?php endif; ?></p>
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-yellow btn-lg"><i class='bx bx-plus'></i> Tambah Tugas</a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/tasks/index.blade.php ENDPATH**/ ?>