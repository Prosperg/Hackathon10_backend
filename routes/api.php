<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Ressources\ProductController;
use App\Http\Controllers\Ressources\CategorieController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\UserRewardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
    Route::patch('/update-user-profile/{id}', [AuthController::class, 'updateUserProfile']);    
});

//get different resource
Route::group([
    'middleware'=>'api',
    'prefix'=>'hackathon'
], function($router){
    Route::get('',function(){return response()->json("API run well");});
});

// Routes publiques
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);
Route::get('events/featured', [EventController::class, 'featured']);
Route::get('events/upcoming', [EventController::class, 'upcoming']);
Route::post('events', [EventController::class, 'store']);
// Routes authentifiées
Route::middleware('auth:sanctum')->group(function () {
    // Routes Événements
    Route::put('events/{event}', [EventController::class, 'update']);
    Route::delete('events/{event}', [EventController::class, 'destroy']);
    
    // Routes Tickets
    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets/purchase', [TicketController::class, 'purchase']);
    Route::post('tickets/reserve', [TicketController::class, 'reserve']);
    Route::post('tickets/{ticket}/transfer', [TicketController::class, 'transfer']);
    Route::get('tickets/{ticket}/qr-code', [TicketController::class, 'generateQRCode']);
    Route::get('tickets/{ticket}/ar-experience', [TicketController::class, 'getARExperience']);
    Route::post('tickets/group-booking', [TicketController::class, 'groupBooking']);
    
    // Routes Récompenses
    Route::get('rewards', [UserRewardController::class, 'index']);
    Route::get('rewards/history', [UserRewardController::class, 'history']);
    Route::get('rewards/available-perks', [UserRewardController::class, 'availablePerks']);
    Route::post('rewards/share-social', [UserRewardController::class, 'shareSocial']);
    Route::post('rewards/refer-friend', [UserRewardController::class, 'referFriend']);
    
    // Routes Organisateur
    Route::get('organizer/events', [EventController::class, 'organizerEvents']);
    Route::get('organizer/statistics', [EventController::class, 'statistics']);
    Route::post('organizer/events/{event}/publish', [EventController::class, 'publish']);
});