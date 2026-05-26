<?php $__env->startSection('title', 'Kalender Tugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <span class="tag-decoration">📅 Kalender</span>
            <h1 class="page-title">Kalender Tugas</h1>
            <p class="page-subtitle">Pantau deadline tugasmu dalam tampilan kalender.</p>
        </div>
        <div>
            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-white btn-lg"><i class='bx bx-list-ul'></i> Mode Daftar</a>
            <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-yellow btn-lg"><i class='bx bx-plus-circle'></i> Tambah Tugas</a>
        </div>
    </div>

    <div class="card" style="padding: 1rem;">
        <div id='calendar'></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var rawTasks = <?php echo json_encode($tasks, 15, 512) ?>;
    
    var eventsData = rawTasks.map(function(task) {
        var color = '#06D6A0'; // low
        if (task.priority === 'medium') color = '#FF9F1C';
        if (task.priority === 'high') color = '#FF4444';
        if (task.is_completed) color = '#bbbbbb'; // Grayed out if completed

        // FullCalendar expects YYYY-MM-DD
        // Ensure task.deadline is formatted or substringed correctly if it's a full ISO string
        var startStr = task.deadline;
        if (startStr && startStr.length > 10) {
            startStr = startStr.substring(0, 10);
        }

        return {
            title: task.title,
            start: startStr,
            color: color,
            url: '<?php echo e(url('/tasks')); ?>/' + task.id + '/edit'
        };
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            list: 'Agenda'
        },
        locale: 'id',
        events: eventsData,
        eventClick: function(info) {
            // Biarkan default behavior berjalan (membuka URL)
        }
    });

    calendar.render();
});
</script>

<style>
/* Styling khusus kalender agar sesuai tema neobrutalism */
.fc .fc-toolbar-title {
    font-weight: 800;
    font-size: 1.5rem;
    color: var(--black);
}
.fc .fc-button-primary {
    background-color: #fff;
    color: var(--black);
    border: var(--border);
    box-shadow: 2px 2px 0px #1A1A1A;
    border-radius: var(--radius);
    text-transform: capitalize;
    font-weight: bold;
}
.fc .fc-button-primary:hover {
    background-color: var(--yellow);
    color: var(--black);
}
.fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: var(--yellow);
    color: var(--black);
    transform: translate(2px, 2px);
    box-shadow: none;
}
.fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid {
    border-color: var(--black) !important;
}
.fc-daygrid-day.fc-day-today {
    background-color: rgba(255, 209, 102, 0.2) !important;
}
.fc-event {
    border: 2px solid var(--black);
    border-radius: 4px;
    padding: 2px;
    box-shadow: 1px 1px 0px #1A1A1A;
    cursor: pointer;
    font-weight: 600;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC 7 - RPL\Desktop\smart-school-task-manager\temp-laravel\resources\views/tasks/calendar.blade.php ENDPATH**/ ?>