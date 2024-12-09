<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_category_id',
        'price',
        'status',
        'seat_number',
        'qr_code',
        'unique_identifier',
        'purchase_date',
        'check_in_time',
        'special_requests',
        'is_transferable',
        'transfer_deadline',
        'ar_experience_enabled',
        'social_sharing_done',
        'group_booking_id'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'check_in_time' => 'datetime',
        'transfer_deadline' => 'datetime',
        'ar_experience_enabled' => 'boolean',
        'social_sharing_done' => 'boolean',
        'special_requests' => 'json'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function generateQRCode()
    {
        $this->unique_identifier = Str::uuid();
        $data = [
            'ticket_id' => $this->id,
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'seat' => $this->seat_number,
            'timestamp' => now()->timestamp
        ];
        
        $this->qr_code = QrCode::size(300)
            ->format('png')
            ->errorCorrection('H')
            ->generate(json_encode($data));
        
        $this->save();
    }

    public function isValidForTransfer(): bool
    {
        return $this->is_transferable && 
               (!$this->transfer_deadline || now()->lt($this->transfer_deadline));
    }

    public function getARExperience()
    {
        if (!$this->ar_experience_enabled) {
            return null;
        }

        return [
            'venue_preview' => $this->event->venue_map_data,
            'seat_visualization' => $this->getSeatVisualization(),
            'special_features' => $this->getSpecialFeatures()
        ];
    }

    private function getSeatVisualization()
    {
        // Logique pour la visualisation AR du siège
        return [
            'seat_number' => $this->seat_number,
            'section' => $this->category->section,
            'view_angle' => $this->calculateViewAngle()
        ];
    }

    private function getSpecialFeatures()
    {
        return [
            'nearest_exits' => $this->getNearestExits(),
            'facilities' => $this->getNearbyFacilities(),
            'accessibility_routes' => $this->getAccessibilityRoutes()
        ];
    }

    public function scopeSold($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('check_in_time');
    }
}
