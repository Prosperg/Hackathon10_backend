<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MotorcycleController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth:api');
    // }
    

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20',
            'motorcycle_number' => 'required|string|max:50',
            'photo' => 'required|image|max:2048', // 2MB max
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Vérifier si la catégorie est active
        $category = TicketCategory::findOrFail($request->ticket_category_id);
        if (!$category->is_active) {
            return response()->json(['error' => 'Cette catégorie de ticket n\'est pas disponible'], 400);
        }

        // Enregistrer la photo
        $photoPath = $request->file('photo')->store('motorcycles', 'public');

        // Créer l'enregistrement
        $motorcycle = Motorcycle::create([
            'phone_number' => $request->phone_number,
            'motorcycle_number' => $request->motorcycle_number,
            'photo_path' => $photoPath,
            'ticket_category_id' => $request->ticket_category_id,
            'user_id' => auth()->id(),
            'entry_time' => now(),
            'notes' => $request->notes
        ]);

        // Générer le ticket et le QR code
        $ticketData = $motorcycle->generateTicket();

        // Créer l'historique
        $motorcycle->history()->create([
            'user_id' => auth()->id(),
            'action' => 'check_in',
            'action_time' => now(),
            'notes' => 'Enregistrement initial',
            'metadata' => [
                'ticket_code' => $motorcycle->ticket_code,
                'category' => $category->name,
                'price' => $category->price
            ]
        ]);

        return response()->json([
            'message' => 'Moto enregistrée avec succès',
            'motorcycle' => $motorcycle,
            'ticket' => $ticketData,
            'photo_url' => Storage::url($photoPath)
        ], 201);
    }

    public function show($id)
    {
        $motorcycle = Motorcycle::with(['user', 'ticketCategory', 'history'])->findOrFail($id);
        $motorcycle->qr_code_content = $motorcycle->getQRCodeContent();
        return response()->json($motorcycle);
    }

    public function markAsPaid($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);
        
        if ($motorcycle->payment_status) {
            return response()->json(['message' => 'Le paiement a déjà été effectué'], 400);
        }

        $motorcycle->markAsPaid();

        return response()->json([
            'message' => 'Paiement enregistré avec succès',
            'motorcycle' => $motorcycle,
            'qr_code_url' => $motorcycle->getQRCodeUrl(),
            'qr_code_content' => $motorcycle->getQRCodeContent()
        ]);
    }

    public function return($id)
    {
        $motorcycle = Motorcycle::findOrFail($id);
        
        if ($motorcycle->status === 'returned') {
            return response()->json(['message' => 'Cette moto a déjà été restituée'], 400);
        }

        if (!$motorcycle->payment_status) {
            return response()->json(['message' => 'Le paiement doit être effectué avant la restitution'], 400);
        }

        $motorcycle->status = 'returned';
        $motorcycle->return_time = now();
        $motorcycle->save();

        $motorcycle->history()->create([
            'user_id' => auth()->id(),
            'action' => 'return',
            'action_time' => now(),
            'notes' => 'Moto restituée'
        ]);

        return response()->json([
            'message' => 'Moto restituée avec succès',
            'motorcycle' => $motorcycle
        ]);
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
