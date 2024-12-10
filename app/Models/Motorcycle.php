<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Motorcycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'motorcycle_number',
        'photo_path',
        'payment_status',
        'user_id',
        'ticket_category_id',
        'ticket_code',
        'qr_code_path',
        'status',
        'entry_time',
        'return_time',
        'notes'
    ];

    protected $casts = [
        'payment_status' => 'boolean',
        'entry_time' => 'datetime',
        'return_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(MotorcycleHistory::class);
    }

    public function generateTicketCode()
    {
        $category = $this->category;
        $count = static::whereDate('created_at', today())->count() + 1;
        $this->ticket_code = sprintf(
            "%s-%05d",
            $category->signature,
            $count
        );
        $this->save();
    }

    public function generateQRCode()
    {
        $data = [
            'ticket_code' => $this->ticket_code,
            'motorcycle_number' => $this->motorcycle_number,
            'entry_time' => $this->entry_time->format('Y-m-d H:i:s'),
            'category' => $this->category->name
        ];

        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate(json_encode($data));

        $filename = 'qrcodes/' . $this->ticket_code . '.svg';
        Storage::disk('public')->put($filename, $qrCode);

        $this->qr_code_path = $filename;
        $this->save();
    }

    public function scopeInCustody($query)
    {
        return $query->where('status', 'in_custody');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function isExpired()
    {
        if (!$this->entry_time || !$this->category) {
            return false;
        }

        $expirationTime = $this->entry_time->addHours($this->category->duration_hours);
        return now()->gt($expirationTime);
    }

    public function getRemainingTime()
    {
        if (!$this->entry_time || !$this->category) {
            return 0;
        }

        $expirationTime = $this->entry_time->addHours($this->category->duration_hours);
        return max(0, now()->diffInMinutes($expirationTime));
    }
}
