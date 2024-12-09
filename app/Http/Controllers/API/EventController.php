<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReward;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtres
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('date')) {
            $query->whereDate('start_date', $request->date);
        }

        if ($request->has('price_range')) {
            $range = explode('-', $request->price_range);
            $query->whereBetween('base_price', $range);
        }

        // Tri
        $sortBy = $request->sort_by ?? 'start_date';
        $order = $request->order ?? 'asc';
        $query->orderBy($sortBy, $order);

        // Pagination avec recommandations
        $events = $query->paginate(10);
        
        if (Auth::check()) {
            $userReward = UserReward::where('user_id', Auth::id())->first();
            if ($userReward) {
                foreach ($events as $event) {
                    $event->available_perks = $userReward->getAvailablePerks();
                    $event->dynamic_price = $event->getDynamicPrice();
                }
            }
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string',
            'max_capacity' => 'required|integer|min:1',
            'category' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'dynamic_pricing_enabled' => 'boolean',
            'ar_content_url' => 'nullable|url',
            'virtual_tour_enabled' => 'boolean',
            'early_bird_deadline' => 'nullable|date|before:start_date',
            'early_bird_discount' => 'nullable|numeric|min:0|max:100',
            'venue_map_data' => 'nullable|json',
            'social_sharing_bonus' => 'numeric|min:0',
            'group_discount_threshold' => 'integer|min:0',
            'weather_policy' => 'nullable|string'
        ]);

        $validated['organizer_id'] = Auth::id();
        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        $event->load(['tickets', 'organizer']);
        
        if (Auth::check()) {
            $userReward = UserReward::where('user_id', Auth::id())->first();
            if ($userReward) {
                $event->available_perks = $userReward->getAvailablePerks();
            }
        }

        $event->dynamic_price = $event->getDynamicPrice();
        $event->available_rewards = $event->getAvailableRewards();

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'start_date' => 'date|after:now',
            'end_date' => 'date|after:start_date',
            'location' => 'string',
            'max_capacity' => 'integer|min:1',
            'category' => 'string',
            'base_price' => 'numeric|min:0',
            'dynamic_pricing_enabled' => 'boolean',
            'ar_content_url' => 'nullable|url',
            'virtual_tour_enabled' => 'boolean',
            'early_bird_deadline' => 'nullable|date|before:start_date',
            'early_bird_discount' => 'nullable|numeric|min:0|max:100',
            'venue_map_data' => 'nullable|json',
            'social_sharing_bonus' => 'numeric|min:0',
            'group_discount_threshold' => 'integer|min:0',
            'weather_policy' => 'nullable|string',
            'status' => 'in:draft,published,cancelled,completed'
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $event->delete();
        return response()->json(null, 204);
    }
}
