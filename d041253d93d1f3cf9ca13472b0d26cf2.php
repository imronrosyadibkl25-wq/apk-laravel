<?php $__env->startSection('title', 'Statistik Tugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <div style="margin-bottom: 1.5rem;">
        <span class="tag-decoration">📊 Statistik Saya</span>
        <h1 class="page-title">Statistik Tugas</h1>
        <p class="page-subtitle">Rekam jejak dan analisis tugas sekolahmu.</p>
    </div>

    <!-- General Stats -->
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card yellow">
            <div class="stat-icon">📚</div>
            <div class="stat-number"><?php echo e($totalTasks); ?></div>
            <div class="stat-label">Total Tugas</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?php echo e($completedTasks); ?></div>
            <div class="stat-label">Tugas Selesai</div>
        </div>
        <div class="stat-card pink">
            <div class="stat-icon">⏳</div>
            <div class="stat-number"><?php echo e($pendingTasks); ?></div>
            <div class="stat-label">Belum Selesai</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        
        <!-- Status Completion Chart (Donut) -->
        <div class="card">
            <h3 class="section-title"><i class='bx bx-pie-chart-alt-2'></i> Status Penyelesaian</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="completionChart"></canvas>
            </div>
        </div>

        <!-- Priority Chart (Pie) -->
        <div class="card">
            <h3 class="section-title"><i class='bx bxs-flag-alt'></i> Prioritas Tugas</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
        
    </div>

    <!-- Daily Tasks Chart (Line) -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 class="section-title"><i class='bx bx-line-chart'></i> Tugas Ditambahkan (30 Hari Terakhir)</h3>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    <!-- Subject Chart (Bar) -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 class="section-title"><i class='bx bx-bar-chart-alt-2'></i> Tugas Berdasarkan Mapel</h3>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="subjectChart"></canvas>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Common Neobrutalism Chart Settings
    Chart.defaults.font.family = "'Space Grotesk', sans-serif";
    Chart.defaults.font.size = 14;
    Chart.defaults.font.weight = '600';
    Chart.defaults.color = '#1A1A1A';
    
    const neoBorder = '#1A1A1A';
    const neoBorderWidth = 3;
    const colors = {
        yellow: '#FFD600',
        pink: '#FF6B9D',
        blue: '#00B4D8',
        green: '#06D6A0',
        red: '#FF4444',
        orange: '#FF9F1C',
        purple: '#B388FF',
        white: '#FFFFFF',
    };

    // 1. Completion Chart (Donut)
    const ctxCompletion = document.getElementById('completionChart').getContext('2d');
    new Chart(ctxCompletion, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Belum Selesai'],
            datasets: [{
                data: [<?php echo e($completionData['Selesai']); ?>, <?php echo e($completionData['Belum Selesai']); ?>],
                backgroundColor: [colors.green, colors.pink],
                borderColor: neoBorder,
                borderWidth: neoBorderWidth,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '60%',
            elements: { arc: { borderRadius: 4 } }
        }
    });

    // 2. Priority Chart (Pie)
    const ctxPriority = document.getElementById('priorityChart').getContext('2d');
    new Chart(ctxPriority, {
        type: 'pie',
        data: {
            labels: ['Tinggi', 'Sedang', 'Rendah'],
            datasets: [{
                data: [<?php echo e($priorityData['high']); ?>, <?php echo e($priorityData['medium']); ?>, <?php echo e($priorityData['low']); ?>],
                backgroundColor: [colors.red, colors.orange, colors.green],
                borderColor: neoBorder,
                borderWidth: neoBorderWidth,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            elements: { arc: { borderRadius: 4 } }
        }
    });

    // 3. Daily Chart (Line)
    const ctxDaily = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dailyLabels); ?>,
            datasets: [{
                label: 'Tugas Baru',
                data: <?php echo json_encode($dailyValues); ?>,
                backgroundColor: colors.blue,
                borderColor: neoBorder,
                borderWidth: neoBorderWidth,
                pointBackgroundColor: colors.yellow,
                pointBorderColor: neoBorder,
                pointBorderWidth: neoBorderWidth,
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0, // Sharp corners for brutalism (no curve)
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.1)', borderColor: neoBorder }
                },
                x: {
                    grid: { display: false, borderColor: neoBorder }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 4. Subject Chart (Bar)
    const ctxSubject = document.getElementById('subjectChart').getContext('2d');
    const subjectLabels = <?php echo json_encode(array_keys($subjectData)); ?>;
    const subjectValues = <?php echo json_encode(array_values($subjectData)); ?>;
    
    // Generate an array of colors for the bars
    const barColors = [colors.blue, colors.pink, colors.yellow, colors.purple, colors.orange, colors.green, colors.red];
    const bgColors = subjectLabels.map((_, i) => barColors[i % barColors.length]);

    new Chart(ctxSubject, {
        type: 'bar',
        data: {
            labels: subjectLabels,
            datasets: [{
                label: 'Jumlah Tugas',
                data: subjectValues,
                backgroundColor: bgColors,
                borderColor: neoBorder,
                borderWidth: neoBorderWidth,
                borderRadius: 4,
                borderSkipped: false, // Ensure border around all sides of bar
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.1)', borderColor: neoBorder }
                },
                x: {
                    grid: { display: false, borderColor: neoBorder }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/statistics.blade.php ENDPATH**/ ?>