<?php $__env->startSection('title', 'Verifikasi Email'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📧</div>
            <h1 class="auth-title">Verifikasi Email</h1>
            <p class="auth-subtitle">Terima kasih sudah mendaftar! Silakan verifikasi email kamu dengan klik link yang sudah kami kirimkan. Jika tidak menerima email, kami akan mengirim ulang.</p>
        </div>

        <?php if(session('status') == 'verification-link-sent'): ?>
            <div class="alert alert-success">
                Link verifikasi baru sudah dikirim ke alamat email yang kamu berikan saat registrasi.
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-yellow btn-lg">
                    <i class='bx bx-mail-send'></i> Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-white btn-lg">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>