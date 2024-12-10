<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\MotorcycleHistory;
use App\Models\TicketCategory;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        // Validation des données
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'motorcycle_number' => 'required|string|max:50',
            'photo' => 'required|image|max:2048', // 2MB max
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'notes' => 'nullable|string'
        ]);

        // Vérifier si la catégorie est active
        $category = TicketCategory::findOrFail($request->ticket_category_id);
        if (!$category->is_active) {
            return response()->json(['error' => 'Cette catégorie de ticket n\'est pas disponible'], 400);
        }

        try {
            // Enregistrer la photo
            $photoPath = $request->file('photo')->store('motorcycles', 'public');

            // Créer la moto
            $motorcycle = Motorcycle::create([
                'phone_number' => $request->phone_number,
                'motorcycle_number' => $request->motorcycle_number,
                'photo_path' => $photoPath,
                'ticket_category_id' => $request->ticket_category_id,
                'user_id' => Auth::id() ?? 1, // Utiliser 1 comme ID par défaut si non authentifié
                'entry_time' => now(),
                'status' => 'in_custody',
                'notes' => $request->notes
            ]);

            // Générer le code du ticket
            $motorcycle->generateTicketCode();

            // Générer le QR code
            $motorcycle->generateQRCode();

            // Créer l'historique
            MotorcycleHistory::create([
                'motorcycle_id' => $motorcycle->id,
                'user_id' => Auth::id() ?? 1,
                'action' => 'check_in',
                'action_time' => now(),
                'notes' => 'Enregistrement initial'
            ]);

            // Préparer les données pour le SMS
            $ticketData = [
                'ticket_code' => $motorcycle->ticket_code,
                'category' => $category->name,
                'price' => $category->price,
                'duration' => $category->duration_hours,
                'plate_number' => $motorcycle->motorcycle_number
            ];

            // Envoyer le SMS de confirmation
            $this->smsService->sendTicketConfirmation(
                $motorcycle->phone_number,
                $ticketData
            );

            return response()->json([
                'message' => 'Moto enregistrée avec succès',
                'motorcycle' => $motorcycle,
                'photo_url' => Storage::url($photoPath)
            ], 201);

        } catch (\Exception $e) {
            // En cas d'erreur, supprimer la photo si elle a été uploadée
            if (isset($photoPath) && Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }

            return response()->json([
                'message' => 'Erreur lors de l\'enregistrement de la moto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $motorcycles = Motorcycle::with(['user', 'category'])
            ->where('status', 'in_custody')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($motorcycles);
    }

    public function show($id)
    {
        $motorcycle = Motorcycle::with(['user', 'category', 'history'])
            ->findOrFail($id);

        return response()->json($motorcycle);
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
            'user_id' => Auth::id() ?? 1,
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
            'user_id' => Auth::id() ?? 1,
            'action' => 'return',
            'action_time' => now()
        ]);

        return response()->json([
            'message' => 'Moto restituée avec succès',
            'motorcycle' => $motorcycle
        ]);
    }

    public function search(Request $request)
    {
        $query = Motorcycle::with(['user', 'category']);

        if ($request->has('ticket_code')) {
            $query->where('ticket_code', 'like', '%' . $request->ticket_code . '%');
        }

        if ($request->has('motorcycle_number')) {
            $query->where('motorcycle_number', 'like', '%' . $request->motorcycle_number . '%');
        }

        if ($request->has('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $motorcycles = $query->orderBy('created_at', 'desc')->get();

        return response()->json($motorcycles);
    }

    public function getCategories()
    {
        $categories = TicketCategory::where('is_active', true)
            ->orderBy('duration_hours')
            ->get();

        return response()->json($categories);
    }

    public function verifyTicket($code)
    {
        $motorcycle = Motorcycle::with(['category'])
            ->where('ticket_code', $code)
            ->first();

        if (!$motorcycle) {
            return response()->json([
                'message' => 'Ticket non trouvé'
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket vérifié avec succès',
            'motorcycle' => $motorcycle,
            'is_expired' => $motorcycle->isExpired(),
            'remaining_time' => $motorcycle->getRemainingTime()
        ]);
    }
}
