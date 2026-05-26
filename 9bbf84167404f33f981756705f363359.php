<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🔄</div>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Buat password baru untuk akun kamu.</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.store')); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

            <div class="form-group">
                <label class="form-label"><i class='bx bx-envelope'></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?php echo e(old('email', $request->email)); ?>" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label class="form-label"><i class='bx bx-lock-alt'></i> Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label class="form-label"><i class='bx bx-lock'></i> Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-yellow btn-lg" style="width: 100%; margin-top: 0.5rem;">
                <i class='bx bx-reset'></i> Reset Password
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>