<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'level',
        'badges',
        'achievements',
        'total_events_attended',
        'consecutive_events',
        'social_shares',
        'referral_count',
        'vip_status',
        'special_perks',
        'milestone_progress'
    ];

    protected $casts = [
        'badges' => 'array',
        'achievements' => 'array',
        'special_perks' => 'array',
        'milestone_progress' => 'json',
        'vip_status' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addPoints(int $points, string $reason)
    {
        $this->points += $points;
        $this->checkLevelUp();
        $this->checkMilestones();
        $this->save();

        // Enregistrer l'historique des points
        UserRewardHistory::create([
            'user_reward_id' => $this->id,
            'points' => $points,
            'reason' => $reason,
            'current_total' => $this->points
        ]);
    }

    public function checkLevelUp()
    {
        $newLevel = floor($this->points / 1000) + 1;
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
            $this->unlockLevelPerks($newLevel);
        }
    }

    public function unlockLevelPerks(int $level)
    {
        $perks = [
            2 => ['early_access' => true],
            3 => ['discount_percentage' => 5],
            4 => ['vip_lounge' => true],
            5 => ['priority_booking' => true],
            6 => ['exclusive_events' => true],
            7 => ['backstage_access' => true],
            8 => ['meet_and_greet' => true],
            9 => ['custom_badge' => true],
            10 => ['vip_status' => true]
        ];

        if (isset($perks[$level])) {
            $this->special_perks = array_merge($this->special_perks ?? [], $perks[$level]);
        }
    }

    public function awardBadge(string $badge)
    {
        if (!in_array($badge, $this->badges ?? [])) {
            $this->badges = array_merge($this->badges ?? [], [$badge]);
            $this->save();
        }
    }

    public function updateMilestoneProgress(string $milestone, int $progress)
    {
        $milestones = $this->milestone_progress ?? [];
        $milestones[$milestone] = ($milestones[$milestone] ?? 0) + $progress;
        $this->milestone_progress = $milestones;
        $this->save();

        $this->checkMilestoneCompletion($milestone);
    }

    private function checkMilestoneCompletion(string $milestone)
    {
        $requirements = [
            'event_attendance' => 10,
            'social_shares' => 20,
            'referrals' => 5,
            'consecutive_events' => 3
        ];

        if (isset($requirements[$milestone]) && 
            ($this->milestone_progress[$milestone] ?? 0) >= $requirements[$milestone]) {
            $this->awardBadge("{$milestone}_master");
            $this->addPoints(500, "Milestone {$milestone} completed");
        }
    }

    public function getAvailablePerks()
    {
        return [
            'level_perks' => $this->special_perks,
            'badges' => $this->badges,
            'vip_status' => $this->vip_status,
            'current_level' => $this->level,
            'points_to_next_level' => (($this->level * 1000) - $this->points),
            'achievements' => $this->achievements
        ];
    }
}
