<?php $__env->startSection('title', 'Konfirmasi Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🔒</div>
            <h1 class="auth-title">Konfirmasi Password</h1>
            <p class="auth-subtitle">Area ini aman. Silakan konfirmasi password kamu sebelum melanjutkan.</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.confirm')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label"><i class='bx bx-lock-alt'></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-yellow btn-lg" style="width: 100%; margin-top: 0.5rem;">
                <i class='bx bx-check-shield'></i> Konfirmasi
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/auth/confirm-password.blade.php ENDPATH**/ ?>