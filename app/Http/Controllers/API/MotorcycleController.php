<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\MotorcycleHistory;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class MotorcycleController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'motorcycle_number' => 'required|string',
            'photo_path' => 'required|string',
            'ticket_category_id' => 'required|exists:ticket_categories,id'
        ]);

        // Créer la moto
        $motorcycle = Motorcycle::create([
            'phone_number' => $request->phone_number,
            'motorcycle_number' => $request->motorcycle_number,
            'photo_path' => $request->photo_path,
            'user_id' => Auth::id(),
            'ticket_category_id' => $request->ticket_category_id,
            'entry_time' => now(),
            'status' => 'in_custody'
        ]);

        // Générer le code du ticket
        $motorcycle->generateTicketCode();

        // Générer le QR code
        $motorcycle->generateQRCode();

        // Créer l'historique
        MotorcycleHistory::create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => Auth::id(),
            'action' => 'check_in',
            'action_time' => now()
        ]);

        // Envoyer le SMS de confirmation
        $ticketData = [
            'ticket_code' => $motorcycle->ticket_code,
            'category' => $motorcycle->category->name,
            'price' => $motorcycle->category->price,
            'duration' => $motorcycle->category->duration_hours,
            'plate_number' => $motorcycle->motorcycle_number
        ];
        
        $this->smsService->sendTicketConfirmation(
            $motorcycle->phone_number,
            $ticketData
        );

        return response()->json([
            'message' => 'Moto enregistrée avec succès',
            'motorcycle' => $motorcycle
        ], 201);
    }

    public function markAsPaid($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);
        
        if ($motorcycle->payment_status) {
            return response()->json([
                'message' => 'Le ticket a déjà été payé'
            ], 400);
        }

        $motorcycle->payment_status = true;
        $motorcycle->save();

        // Créer l'historique
        MotorcycleHistory::create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => Auth::id(),
            'action' => 'payment',
            'action_time' => now()
        ]);

        // Envoyer le SMS de confirmation de paiement
        $paymentData = [
            'amount' => $motorcycle->category->price,
            'ticket_code' => $motorcycle->ticket_code,
            'date' => now()->format('d/m/Y H:i')
        ];

        $this->smsService->sendPaymentConfirmation(
            $motorcycle->phone_number,
            $paymentData
        );

        return response()->json([
            'message' => 'Paiement enregistré avec succès',
            'motorcycle' => $motorcycle
        ]);
    }

    public function return($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);

        if ($motorcycle->status === 'returned') {
            return response()->json([
                'message' => 'Cette moto a déjà été restituée'
            ], 400);
        }

        if (!$motorcycle->payment_status) {
            return response()->json([
                'message' => 'Le paiement doit être effectué avant la restitution'
            ], 400);
        }

        $motorcycle->status = 'returned';
        $motorcycle->return_time = now();
        $motorcycle->save();

        // Créer l'historique
        MotorcycleHistory::create([
            'motorcycle_id' => $motorcycle->id,
            'user_id' => Auth::id(),
            'action' => 'return',
            'action_time' => now()
        ]);

        return response()->json([
            'message' => 'Moto restituée avec succès',
            'motorcycle' => $motorcycle
        ]);
    }

    public function index()
    {
        $motorcycles = Motorcycle::with(['user', 'ticketCategory'])
            ->inCustody()
            ->today()
            ->get()
            ->map(function ($motorcycle) {
                $motorcycle->qr_code_content = $motorcycle->getQRCodeContent();
                return $motorcycle;
            });

        return response()->json($motorcycles);
    }

    public function show($id)
    {
        $motorcycle = Motorcycle::with(['user', 'ticketCategory', 'history'])->findOrFail($id);
        $motorcycle->qr_code_content = $motorcycle->getQRCodeContent();
        return response()->json($motorcycle);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $search = $request->search;

        $motorcycles = Motorcycle::where(function($query) use ($search) {
            $query->where('phone_number', 'like', "%{$search}%")
                  ->orWhere('motorcycle_number', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%");
        })
        ->with(['ticketCategory'])
        ->inCustody()
        ->get()
        ->map(function ($motorcycle) {
            $motorcycle->qr_code_content = $motorcycle->getQRCodeContent();
            return $motorcycle;
        });

        return response()->json($motorcycles);
    }

    public function getCategories()
    {
        $categories = TicketCategory::where('is_active', true)->get();
        return response()->json($categories);
    }

    public function verifyTicket($code)
    {
        $motorcycle = Motorcycle::where('ticket_code', $code)
            ->with(['ticketCategory'])
            ->firstOrFail();

        $validUntil = $motorcycle->entry_time->addHours($motorcycle->ticketCategory->duration_hours);
        $isValid = now()->lt($validUntil) && $motorcycle->payment_status;

        return response()->json([
            'motorcycle' => $motorcycle,
            'is_valid' => $isValid,
            'valid_until' => $validUntil,
            'status' => $motorcycle->status,
            'qr_code_content' => $motorcycle->getQRCodeContent()
        ]);
    }
}
