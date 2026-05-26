<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_name',
        'avatar',
        'xp',
        'level',
        'last_quest_reset_date',
        'completed_quests_today',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'completed_quests_today' => 'array',
        ];
    }

    public function xpForNextLevel()
    {
        return $this->level * 150;
    }

    public function xpPercentage()
    {
        $next = $this->xpForNextLevel();
        if ($next <= 0) return 0;
        return min(100, round(($this->xp / $next) * 100));
    }

    public function levelTitle()
    {
        if ($this->level >= 15) {
            return '🏆 Legenda Sekolah';
        } elseif ($this->level >= 10) {
            return '⚡ Ahli Tugas';
        } elseif ($this->level >= 5) {
            return '📚 Kutu Buku';
        }
        return '🎓 Siswa Baru';
    }

    public function addXp(int $amount)
    {
        $this->xp += $amount;
        $levelUp = false;
        
        while ($this->xp >= $this->xpForNextLevel()) {
            $this->xp -= $this->xpForNextLevel();
            $this->level++;
            $levelUp = true;
        }
        
        $this->save();
        return $levelUp;
    }

    public function deductXp(int $amount)
    {
        $this->xp -= $amount;
        
        while ($this->xp < 0 && $this->level > 1) {
            $this->level--;
            $this->xp += $this->xpForNextLevel();
        }
        
        if ($this->xp < 0) {
            $this->xp = 0;
        }
        
        $this->save();
    }

    public function checkDailyQuests(string $actionType, array $context = [])
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $completedQuests = $this->completed_quests_today ?? [];
        
        // Reset if new day
        if ($this->last_quest_reset_date !== $today) {
            $completedQuests = [];
            $this->last_quest_reset_date = $today;
            $this->completed_quests_today = $completedQuests;
            $this->save();
        }
        
        $unlockedQuests = [];
        
        if ($actionType === 'create_task') {
            if (!in_array(3, $completedQuests)) {
                $completedQuests[] = 3;
                $this->completed_quests_today = $completedQuests;
                $levelUp = $this->addXp(20);
                $unlockedQuests[] = [
                    'id' => 3,
                    'title' => 'Tambahkan 1 tugas baru hari ini',
                    'xp' => 20,
                    'level_up' => $levelUp
                ];
            }
        }
        
        if ($actionType === 'complete_task') {
            if (!in_array(1, $completedQuests)) {
                $completedQuests[] = 1;
                $this->completed_quests_today = $completedQuests;
                $levelUp = $this->addXp(30);
                $unlockedQuests[] = [
                    'id' => 1,
                    'title' => 'Selesaikan 1 tugas hari ini',
                    'xp' => 30,
                    'level_up' => $levelUp
                ];
            }
            
            $priority = $context['priority'] ?? 'low';
            if (($priority === 'high' || $priority === 'medium') && !in_array(2, $completedQuests)) {
                $completedQuests[] = 2;
                $this->completed_quests_today = $completedQuests;
                $levelUp = $this->addXp(50);
                $unlockedQuests[] = [
                    'id' => 2,
                    'title' => 'Selesaikan tugas High/Medium hari ini',
                    'xp' => 50,
                    'level_up' => $levelUp
                ];
            }
        }
        
        if (!empty($unlockedQuests)) {
            $this->save();
        }
        
        return $unlockedQuests;
    }
}
