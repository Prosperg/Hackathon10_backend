<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserReward;
use App\Models\UserRewardHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRewardController extends Controller
{
    public function index()
    {
        $userReward = UserReward::where('user_id', Auth::id())->firstOrCreate([
            'user_id' => Auth::id(),
            'points' => 0,
            'level' => 1
        ]);

        return response()->json([
            'rewards' => $userReward,
            'next_level' => $userReward->getNextLevelRequirements(),
            'available_perks' => $userReward->getAvailablePerks()
        ]);
    }

    public function history()
    {
        $history = UserRewardHistory::whereHas('userReward', function($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($history);
    }

    public function availablePerks()
    {
        $userReward = UserReward::where('user_id', Auth::id())->firstOrFail();
        return response()->json($userReward->getAvailablePerks());
    }

    public function shareSocial(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|in:facebook,twitter,instagram,linkedin',
            'event_id' => 'required|exists:events,id'
        ]);

        $userReward = UserReward::where('user_id', Auth::id())->firstOrFail();
        
        // Vérifier si l'utilisateur n'a pas déjà partagé cet événement
        if (!$userReward->hasSharedEvent($request->event_id, $request->platform)) {
            $userReward->addPoints(50, "Partage social sur {$request->platform}");
            $userReward->incrementSocialShares();
            
            return response()->json([
                'message' => 'Points ajoutés pour le partage social',
                'points_earned' => 50,
                'current_points' => $userReward->points
            ]);
        }

        return response()->json([
            'message' => 'Vous avez déjà partagé cet événement sur cette plateforme'
        ], 400);
    }

    public function referFriend(Request $request)
    {
        $request->validate([
            'friend_email' => 'required|email|unique:users,email'
        ]);

        $userReward = UserReward::where('user_id', Auth::id())->firstOrFail();
        
        // Générer un code de parrainage unique
        $referralCode = $userReward->generateReferralCode();

        // Envoyer l'email d'invitation (à implémenter)
        // Mail::to($request->friend_email)->send(new ReferralInvitation($referralCode));

        return response()->json([
            'message' => 'Invitation envoyée avec succès',
            'referral_code' => $referralCode
        ]);
    }

    public function claimReward(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id'
        ]);

        $userReward = UserReward::where('user_id', Auth::id())->firstOrFail();
        
        if ($userReward->canClaimReward($request->reward_id)) {
            $reward = $userReward->claimReward($request->reward_id);
            
            return response()->json([
                'message' => 'Récompense réclamée avec succès',
                'reward' => $reward
            ]);
        }

        return response()->json([
            'message' => 'Vous ne pouvez pas réclamer cette récompense'
        ], 400);
    }
}
