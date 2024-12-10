<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Motorcycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'motorcycle_number',
        'photo_path',
        'payment_status',
        'user_id',
        'status',
        'entry_time',
        'return_time',
        'notes',
        'ticket_category_id',
        'ticket_code'
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

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(MotorcycleHistory::class);
    }

    public function generateTicket()
    {
        // Générer un code unique
        $this->ticket_code = strtoupper(Str::random(8));
        
        // Générer le QR code
        $qrData = [
            'ticket_code' => $this->ticket_code,
            'motorcycle_number' => $this->motorcycle_number,
            'entry_time' => $this->entry_time->format('Y-m-d H:i:s'),
            'category' => $this->ticketCategory->name,
            'price' => $this->ticketCategory->price
        ];

        // Générer le QR code en SVG
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate(json_encode($qrData));

        // Sauvegarder le QR code
        $qrPath = 'qrcodes/' . $this->ticket_code . '.svg';
        Storage::disk('public')->put($qrPath, $qrCode);

        $this->qr_code_path = $qrPath;
        $this->save();

        return [
            'ticket_code' => $this->ticket_code,
            'qr_code_url' => Storage::url($qrPath),
            'qr_code_content' => $qrCode, // On renvoie aussi le contenu SVG directement
            'price' => $this->ticketCategory->price,
            'valid_until' => $this->entry_time->addHours($this->ticketCategory->duration_hours)
        ];
    }

    public function markAsPaid()
    {
        $this->payment_status = true;
        $this->save();

        // Créer un enregistrement dans l'historique
        $this->history()->create([
            'user_id' => auth()->id(),
            'action' => 'payment',
            'action_time' => now(),
            'notes' => 'Paiement effectué'
        ]);

        // Envoyer les SMS
        $this->sendPaymentConfirmationSMS();
    }

    private function sendPaymentConfirmationSMS()
    {
        $ticketInfo = "Code: {$this->ticket_code}";
        $validUntil = $this->entry_time->addHours($this->ticketCategory->duration_hours)->format('d/m/Y H:i');
        
        // Message pour le client
        $clientMessage = "Votre moto a été enregistrée avec succès.\n"
            . "Numéro: {$this->motorcycle_number}\n"
            . "{$ticketInfo}\n"
            . "Valide jusqu'au: {$validUntil}\n"
            . "Prix: {$this->ticketCategory->price} FCFA";
        
        // Message pour l'agent
        $agentMessage = "Nouvelle moto enregistrée.\n"
            . "Numéro: {$this->motorcycle_number}\n"
            . "Client: {$this->phone_number}\n"
            . "{$ticketInfo}";
        
        // TODO: Implémenter l'envoi SMS via Celtiis
    }

    public function getQRCodeUrl()
    {
        return $this->qr_code_path ? Storage::url($this->qr_code_path) : null;
    }

    public function getQRCodeContent()
    {
        if (!$this->qr_code_path) {
            return null;
        }
        return Storage::disk('public')->get($this->qr_code_path);
    }

    public function scopeInCustody($query)
    {
        return $query->where('status', 'in_custody');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('entry_time', today());
    }
}
