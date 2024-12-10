<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\MotorcycleController;

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
    'middleware' => ['api', 'jwt.auth'],
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