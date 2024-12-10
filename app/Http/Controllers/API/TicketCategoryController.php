<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketCategoryController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::where('is_active', true)
            ->orderBy('duration_hours')
            ->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'signature' => 'required|string|size:3|unique:ticket_categories,signature',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = TicketCategory::create($request->all());

        return response()->json([
            'message' => 'Catégorie créée avec succès',
            'category' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = TicketCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = TicketCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'duration_hours' => 'integer|min:1',
            'signature' => 'string|size:3|unique:ticket_categories,signature,' . $id,
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->all());

        return response()->json([
            'message' => 'Catégorie mise à jour avec succès',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = TicketCategory::findOrFail($id);
        
        // Vérifier si des tickets sont liés à cette catégorie
        if ($category->motorcycles()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer cette catégorie car elle est utilisée par des tickets'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }

    public function toggleActive($id)
    {
        $category = TicketCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json([
            'message' => 'Statut de la catégorie mis à jour',
            'category' => $category
        ]);
    }

    public function getActiveCategories()
    {
        $categories = TicketCategory::where('is_active', true)
            ->orderBy('duration_hours')
            ->get();

        return response()->json($categories);
    }

    public function statistics()
    {
        $categories = TicketCategory::withCount(['motorcycles' => function($query) {
            $query->whereDate('created_at', today());
        }])
        ->withSum(['motorcycles' => function($query) {
            $query->whereDate('created_at', today())
                  ->where('payment_status', true);
        }], 'price')
        ->get()
        ->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'tickets_today' => $category->motorcycles_count,
                'revenue_today' => $category->motorcycles_sum_price ?? 0,
                'signature' => $category->signature
            ];
        });

        return response()->json([
            'statistics' => $categories,
            'total_tickets' => $categories->sum('tickets_today'),
            'total_revenue' => $categories->sum('revenue_today')
        ]);
    }
}
