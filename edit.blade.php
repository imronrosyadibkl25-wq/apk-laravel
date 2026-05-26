@extends('layouts.app')
@section('title', 'Edit Tugas')

@section('content')
<div class="animate-fade-in">
    <div style="max-width: 650px; margin: 0 auto;">
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('tasks.index') }}" class="btn btn-white btn-sm" style="margin-bottom: 1rem;"><i class='bx bx-arrow-back'></i> Kembali</a>
            <span class="tag-decoration">✏️ Edit Tugas</span>
            <h1 class="page-title">Edit Tugas</h1>
            <p class="page-subtitle">Perbarui detail tugas sekolahmu di bawah ini.</p>
        </div>

        <div class="card">
            <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label"><i class='bx bx-edit'></i> Judul Tugas</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Mengerjakan soal halaman 45" value="{{ old('title', $task->title) }}" required autofocus>
                    @error('title') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-align-left'></i> Deskripsi/Catatan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tambahkan detail tugas jika perlu...">{{ old('description', $task->description) }}</textarea>
                    @error('description') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-paperclip'></i> Lampiran File (Opsional, Max 2MB)</label>
                    @if($task->attachment_path)
                        <div style="margin-bottom: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; background: #f9f9f9; display: flex; align-items: center; gap: 10px; border-radius: var(--radius);">
                            <i class='bx bx-file' style="font-size: 1.5rem; color: #555;"></i>
                            <a href="{{ Storage::url($task->attachment_path) }}" target="_blank" style="flex: 1; color: var(--black); text-decoration: none; font-weight: 600;">Lihat Lampiran Saat Ini</a>
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; color: var(--red);">
                                <input type="checkbox" name="remove_attachment" value="1"> Hapus Lampiran
                            </label>
                        </div>
                        <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">Atau unggah file baru untuk menggantinya:</div>
                    @endif
                    <input type="file" name="attachment" class="form-control" style="padding: 10px;">
                    @error('attachment') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-book'></i> Mata Pelajaran</label>
                    <select name="subject" class="form-control" required>
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}" {{ old('subject', $task->subject) == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                        @endforeach
                    </select>
                    @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-calendar'></i> Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $task->deadline->format('Y-m-d')) }}" required>
                    @error('deadline') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label"><i class='bx bx-flag'></i> Prioritas</label>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="low" {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }} style="display:none;" id="priority-low">
                            <div class="priority-option" id="priority-low-label" onclick="selectPriority('low')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🟢 Low</div>
                        </label>
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="medium" {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }} style="display:none;" id="priority-medium">
                            <div class="priority-option" id="priority-medium-label" onclick="selectPriority('medium')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🟡 Medium</div>
                        </label>
                        <label style="flex:1; min-width: 120px;">
                            <input type="radio" name="priority" value="high" {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }} style="display:none;" id="priority-high">
                            <div class="priority-option" id="priority-high-label" onclick="selectPriority('high')" style="text-align:center; padding: 1rem; border: var(--border); border-radius: var(--radius); cursor: pointer; font-weight: 700; transition: all 0.2s;">🔴 High</div>
                        </label>
                    </div>
                    @error('priority') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-yellow btn-lg" style="flex: 1;"><i class='bx bx-save'></i> Perbarui Tugas</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-white btn-lg"><i class='bx bx-x'></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
