<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\MotorcycleController;
use App\Http\Controllers\API\TicketCategoryController;

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

// Routes publiques
Route::get('ticket-categories', [MotorcycleController::class, 'getCategories']);
Route::get('verify-ticket/{code}', [MotorcycleController::class, 'verifyTicket']);

// Routes protégées pour la gestion des motos
Route::group([
    // 'middleware' => ['api', 'jwt.auth'],
], function ($router) {
    // Liste et enregistrement des motos
    Route::get('motorcycles', [MotorcycleController::class, 'index']);
    Route::post('motorcycles', [MotorcycleController::class, 'store']);
    Route::get('motorcycles/{id}', [MotorcycleController::class, 'show']);
    
    // Gestion des paiements et restitutions
    Route::post('motorcycles/{id}/pay', [MotorcycleController::class, 'markAsPaid']);
    Route::post('motorcycles/{id}/return', [MotorcycleController::class, 'return']);
    
    // Recherche
    Route::post('motorcycles/search', [MotorcycleController::class, 'search']);
});

// Routes pour les catégories de tickets
Route::prefix('ticket-categories')->group(function () {
    Route::get('/', [TicketCategoryController::class, 'index']);
    Route::get('/active', [TicketCategoryController::class, 'getActiveCategories']);
    Route::get('/statistics', [TicketCategoryController::class, 'statistics']);
    Route::get('/{id}', [TicketCategoryController::class, 'show']);
    Route::post('/', [TicketCategoryController::class, 'store']);
    Route::put('/{id}', [TicketCategoryController::class, 'update']);
    Route::delete('/{id}', [TicketCategoryController::class, 'destroy']);
    Route::patch('/{id}/toggle-active', [TicketCategoryController::class, 'toggleActive']);
});
// Route de test pour SMS
Route::post('/test-sms', function(Request $request) {
    $smsService = app(App\Services\SmsService::class);
    
    $request->validate([
        'phone_number' => 'required|string'
    ]);

    $testData = [
        'ticket_code' => 'TEST-12345',
        'category' => 'Demi-journée',
        'price' => 300,
        'duration' => 12,
        'plate_number' => 'TEST-123'
    ];

    $result = $smsService->sendTicketConfirmation($request->phone_number, $testData);

    return response()->json([
        'message' => 'Test SMS',
        'result' => $result
    ]);
});