<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Smart School Task Manager')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <meta name="description" content="Smart School Task Manager - Kelola tugas sekolahmu dengan lebih pintar!">
    <link rel="stylesheet" href="<?php echo e(asset('css/neobrutalism.css')); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check local storage for theme preference before rendering body
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="<?php echo e(route('dashboard')); ?>" class="navbar-brand">
                <i class='bx bxs-graduation'></i>
                Smart School Task Manager
            </a>
            <button type="button" class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle Menu">
                <i class='bx bx-menu'></i>
            </button>
        </div>
        <div class="navbar-links" id="navbarLinks">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                <i class='bx bxs-dashboard'></i> Dashboard
            </a>
            <a href="<?php echo e(route('statistics.index')); ?>" class="nav-link <?php echo e(request()->routeIs('statistics.index') ? 'active' : ''); ?>">
                <i class='bx bx-bar-chart-alt-2'></i> Statistik
            </a>
            <a href="<?php echo e(route('tasks.index')); ?>" class="nav-link <?php echo e(request()->routeIs('tasks.index') ? 'active' : ''); ?>">
                <i class='bx bxs-book-content'></i> Tugas
            </a>
            <a href="<?php echo e(route('tasks.create')); ?>" class="nav-link <?php echo e(request()->routeIs('tasks.create') ? 'active' : ''); ?>">
                <i class='bx bx-plus-circle'></i> Tambah
            </a>
            <a href="<?php echo e(route('achievements.index')); ?>" class="nav-link <?php echo e(request()->routeIs('achievements.index') ? 'active' : ''); ?>">
                <i class='bx bxs-trophy'></i> Pencapaian
            </a>
            <button type="button" class="nav-link btn-blue" onclick="openGuideModal()" style="color: var(--white); border-color: var(--black);">
                <i class='bx bx-info-circle'></i> Panduan
            </button>
            <button type="button" class="nav-link" id="theme-toggle" title="Toggle Dark Mode" style="font-size: 1.2rem; padding: 0.3rem 0.6rem;">
                <i class='bx bx-moon'></i>
            </button>
            <div class="nav-user" style="display: flex; align-items: center; gap: 0.8rem; padding: 0.3rem 1rem;">
                <div style="display: flex; flex-direction: column; align-items: flex-start; line-height: 1.2;">
                    <span style="font-size: 0.85rem; font-weight: 700;"><?php echo e(Auth::user()->name); ?></span>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--pink);"><?php echo e(Auth::user()->levelTitle()); ?></span>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 1.2;">
                    <span class="badge-level" style="background: var(--purple); color: var(--white); border: 2px solid var(--black); border-radius: 4px; padding: 0.05rem 0.3rem; font-size: 0.75rem; font-weight: 800;">Lvl <?php echo e(Auth::user()->level); ?></span>
                    <div style="width: 60px; height: 6px; background: #e0e0e0; border: 1px solid var(--black); border-radius: 3px; overflow: hidden; margin-top: 0.2rem;" title="XP: <?php echo e(Auth::user()->xp); ?> / <?php echo e(Auth::user()->xpForNextLevel()); ?>">
                        <div style="width: <?php echo e(Auth::user()->xpPercentage()); ?>%; height: 100%; background: var(--green);"></div>
                    </div>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link" style="font-family:var(--font);">
                    <i class='bx bx-log-out'></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer style="text-align:center; padding:2rem; font-weight:600; border-top:var(--border-thick); background:var(--yellow);">
        <p>📚 Smart School Task Manager &copy; <?php echo e(date('Y')); ?> — Belajar Lebih Pintar!</p>
    </footer>

    <!-- Modal Panduan -->
    <div class="modal-overlay" id="guideModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class='bx bx-info-circle' style="color: var(--blue); font-size: 2rem;"></i>
                    Panduan Penggunaan
                </div>
                <button class="modal-close" onclick="closeGuideModal()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 1rem;">Selamat datang di <strong>Smart School Task Manager</strong>! Berikut adalah cara cepat menggunakan aplikasi ini:</p>
                <ul style="max-height: 50vh; overflow-y: auto; padding-right: 10px;">
                    <li>
                        <i class='bx bxs-dashboard'></i>
                        <div>
                            <strong>1. Dashboard & Statistik</strong>
                            Pantau ringkasan tugasmu dan lihat grafik statistik penyelesaian tugas agar belajarmu lebih terukur.
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-plus-circle'></i>
                        <div>
                            <strong>2. Tambah & Edit Tugas</strong>
                            Klik "Tambah" untuk mencatat tugas baru. Kamu juga bisa mengedit tugas yang sudah ada untuk memperbarui deskripsi atau deadline.
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-paperclip'></i>
                        <div>
                            <strong>3. Lampiran File</strong>
                            Bisa upload file tugas (PDF/Word/Gambar) maksimal 2MB langsung ke aplikasinya.
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-search-alt'></i>
                        <div>
                            <strong>4. Kelola & Cari Tugas</strong>
                            Gunakan kolom pencarian atau filter untuk menemukan tugas dengan cepat. Tandai tugas selesai atau hapus jika sudah tidak relevan.
                        </div>
                    </li>
                    <li>
                        <i class='bx bx-calendar'></i>
                        <div>
                            <strong>5. Mode Kalender</strong>
                            Ubah tampilan daftar tugas menjadi kalender interaktif untuk memantau tenggat waktu (deadline) dengan lebih visual.
                        </div>
                    </li>
                </ul>
                <div class="alert alert-warning" style="margin-top: 1.5rem; margin-bottom: 0;">
                    <i class='bx bx-bulb'></i> <strong>Tips:</strong> Kerjakan tugas dengan prioritas tinggi dan tenggat waktu terdekat lebih dulu!
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            document.getElementById('navbarLinks').classList.toggle('active');
        }

        function openGuideModal() {
            document.getElementById('guideModal').classList.add('active');
        }
        function closeGuideModal() {
            document.getElementById('guideModal').classList.remove('active');
        }
        
        // Tutup modal jika user klik di luar modal (overlay)
        window.onclick = function(event) {
            const modal = document.getElementById('guideModal');
            if (event.target == modal) {
                closeGuideModal();
            }
        }

        // Dark Mode Logic
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = themeToggle.querySelector('i');
        const root = document.documentElement;

        function updateThemeIcon() {
            if (root.getAttribute('data-theme') === 'dark') {
                themeIcon.classList.replace('bx-moon', 'bx-sun');
            } else {
                themeIcon.classList.replace('bx-sun', 'bx-moon');
            }
        }

        updateThemeIcon(); // Set initial icon

        themeToggle.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        });

        // SweetAlert2 for Flash Messages
        <?php if(session('success')): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?php echo e(session('success')); ?>',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'card',
                    confirmButton: 'btn btn-green'
                },
                buttonsStyling: false
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                title: 'Oops!',
                text: '<?php echo e(session('error')); ?>',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'card',
                    confirmButton: 'btn btn-red'
                },
                buttonsStyling: false
            });
        <?php endif; ?>
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>