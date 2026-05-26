<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎓</div>
            <h1 class="auth-title">Selamat Datang!</h1>
            <p class="auth-subtitle">Masuk ke Smart School Task Manager</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if(session('status')): ?>
            <div class="alert alert-success"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label"><i class='bx bx-envelope'></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?php echo e(old('email')); ?>" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label"><i class='bx bx-lock-alt'></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width: 20px; height: 20px; cursor: pointer;">
                <label for="remember" style="font-weight: 600; cursor: pointer;">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-yellow btn-lg" style="width: 100%; margin-top: 0.5rem;">
                <i class='bx bx-log-in'></i> Masuk
            </button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar sekarang</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>