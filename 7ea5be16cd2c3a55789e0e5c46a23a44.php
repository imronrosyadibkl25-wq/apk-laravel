<?php $__env->startSection('title', 'Tambah Tugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <div style="max-width: 650px; margin: 0 auto;">
        <div style="margin-bottom: 1.5rem;">
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-white btn-sm" style="margin-bottom: 1rem;"><i class='bx bx-arrow-back'></i> Kembali</a>
            <span class="tag-decoration">➕ Tugas Baru</span>
            <h1 class="page-title">Tambah Tugas</h1>
            <p class="page-subtitle">Isi detail tugas sekolahmu di bawah ini.</p>
        </div>

        <div class="card">
            <form action="<?php echo e(route('tasks.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label"><i class='bx bx-edit'></i> Judul Tugas</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Mengerjakan soal halaman 45" value="<?php echo e(old('title')); ?>" required autofocus>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-align-left'></i> Deskripsi/Catatan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tambahkan detail tugas jika perlu..."><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-paperclip'></i> Lampiran File (Opsional, Max 2MB)</label>
                    <input type="file" name="attachment" class="form-control" style="padding: 10px;">
                    <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-book'></i> Mata Pelajaran</label>
                    <select name="subject" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject); ?>" <?php echo e(old('subject') == $subject ? 'selected' : ''); ?>><?php echo e($subject); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-calendar'></i> Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="<?php echo e(old('deadline', date('Y-m-d'))); ?>" min="<?php echo e(date('Y-m-d')); ?>" required>
                    <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-flag'></i> Prioritas</label>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="low" <?php echo e(old('priority', 'medium') == 'low' ? 'checked' : ''); ?> style="display:none;" id="priority-low">
                            <div class="priority-option" id="priority-low-label" onclick="selectPriority('low')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🟢 Low</div>
                        </label>
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="medium" <?php echo e(old('priority', 'medium') == 'medium' ? 'checked' : ''); ?> style="display:none;" id="priority-medium">
                            <div class="priority-option" id="priority-medium-label" onclick="selectPriority('medium')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🟡 Medium</div>
                        </label>
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="high" <?php echo e(old('priority', 'medium') == 'high' ? 'checked' : ''); ?> style="display:none;" id="priority-high">
                            <div class="priority-option" id="priority-high-label" onclick="selectPriority('high')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🔴 High</div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-yellow btn-lg" style="flex: 1;"><i class='bx bx-save'></i> Simpan Tugas</button>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-white btn-lg"><i class='bx bx-x'></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function selectPriority(level) {
    document.querySelectorAll('.priority-option').forEach(el => {
        el.style.background = '#fff';
        el.style.boxShadow = 'none';
        el.style.transform = 'none';
    });
    document.getElementById('priority-' + level).checked = true;
    const label = document.getElementById('priority-' + level + '-label');
    const colors = { low: '#06D6A0', medium: '#FF9F1C', high: '#FF4444' };
    label.style.background = colors[level];
    label.style.boxShadow = '4px 4px 0px #1A1A1A';
    label.style.transform = 'translate(-2px, -2px)';
}
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="priority"]:checked');
    if (checked) selectPriority(checked.value);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/tasks/create.blade.php ENDPATH**/ ?>