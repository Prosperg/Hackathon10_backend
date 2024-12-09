<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'max_capacity',
        'organizer_id',
        'category',
        'base_price',
        'is_featured',
        'dynamic_pricing_enabled',
        'ar_content_url',
        'virtual_tour_enabled',
        'early_bird_deadline',
        'early_bird_discount',
        'venue_map_data',
        'social_sharing_bonus',
        'group_discount_threshold',
        'weather_policy',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'early_bird_deadline' => 'datetime',
        'dynamic_pricing_enabled' => 'boolean',
        'virtual_tour_enabled' => 'boolean',
        'venue_map_data' => 'json',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getDynamicPrice()
    {
        if (!$this->dynamic_pricing_enabled) {
            return $this->base_price;
        }

        // Logique de prix dynamique basée sur la demande
        $soldTickets = $this->tickets()->sold()->count();
        $capacity = $this->max_capacity;
        $demandFactor = $soldTickets / $capacity;

        // Augmentation du prix jusqu'à 50% en fonction de la demande
        $priceIncrease = $demandFactor * 0.5;
        return $this->base_price * (1 + $priceIncrease);
    }

    public function getAvailableRewards()
    {
        // Logique pour les récompenses disponibles
        return [
            'early_bird' => $this->isEarlyBirdAvailable(),
            'group_discount' => $this->calculateGroupDiscount(),
            'social_bonus' => $this->social_sharing_bonus
        ];
    }

    private function isEarlyBirdAvailable(): bool
    {
        return now()->lt($this->early_bird_deadline);
    }

    private function calculateGroupDiscount(): float
    {
        // Logique pour les réductions de groupe
        return $this->group_discount_threshold;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}
