<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attachment_path',
        'subject',
        'deadline',
        'priority',
        'is_completed',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDeadlineStatusAttribute()
    {
        if ($this->is_completed) {
            return 'completed';
        }
        $now = Carbon::now()->startOfDay();
        $deadline = $this->deadline->startOfDay();
        if ($deadline->lt($now)) {
            return 'overdue';
        }
        if ($deadline->diffInDays($now) <= 3) {
            return 'near';
        }
        return 'safe';
    }

    public function scopeBySubject($query, $subject)
    {
        if ($subject) {
            return $query->where('subject', $subject);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status === 'completed') {
            return $query->where('is_completed', true);
        }
        if ($status === 'pending') {
            return $query->where('is_completed', false);
        }
        return $query;
    }

    public function scopeByPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }
}
