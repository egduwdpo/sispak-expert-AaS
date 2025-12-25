<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExpertSystemController;
use App\Http\Controllers\API\DiseaseController;
use App\Http\Controllers\API\SymptomController;
use App\Http\Controllers\API\RuleController;
use App\Http\Controllers\API\ConfidenceScaleController;
use App\Http\Controllers\API\ConsultationController;
Route::options('{any}', function (Request $request) {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept');
})->where('any', '.*');
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Expert Systems
    Route::get('/expert-systems', [ExpertSystemController::class, 'index']);
    Route::post('/expert-systems', [ExpertSystemController::class, 'store']);
    Route::get('/expert-systems/{id}', [ExpertSystemController::class, 'show']);
    Route::put('/expert-systems/{id}', [ExpertSystemController::class, 'update']);
    Route::delete('/expert-systems/{id}', [ExpertSystemController::class, 'destroy']);
    Route::get('/expert-systems/stats/dashboard', [ExpertSystemController::class, 'statistics']);

    // Diseases
    Route::get('/expert-systems/{expertSystemId}/diseases', [DiseaseController::class, 'index']);
    Route::post('/expert-systems/{expertSystemId}/diseases', [DiseaseController::class, 'store']);
    Route::put('/expert-systems/{expertSystemId}/diseases/{id}', [DiseaseController::class, 'update']);
    Route::delete('/expert-systems/{expertSystemId}/diseases/{id}', [DiseaseController::class, 'destroy']);

    // Symptoms
    Route::get('/expert-systems/{expertSystemId}/symptoms', [SymptomController::class, 'index']);
    Route::post('/expert-systems/{expertSystemId}/symptoms', [SymptomController::class, 'store']);
    Route::put('/expert-systems/{expertSystemId}/symptoms/{id}', [SymptomController::class, 'update']);
    Route::delete('/expert-systems/{expertSystemId}/symptoms/{id}', [SymptomController::class, 'destroy']);

    // Rules
    Route::get('/expert-systems/{expertSystemId}/rules', [RuleController::class, 'index']);
    Route::post('/expert-systems/{expertSystemId}/rules', [RuleController::class, 'store']);
    Route::put('/expert-systems/{expertSystemId}/rules/{id}', [RuleController::class, 'update']);
    Route::delete('/expert-systems/{expertSystemId}/rules/{id}', [RuleController::class, 'destroy']);

    // Confidence Scales
    Route::get('/expert-systems/{expertSystemId}/confidence-scales', [ConfidenceScaleController::class, 'index']);
    Route::post('/expert-systems/{expertSystemId}/confidence-scales/bulk', [ConfidenceScaleController::class, 'bulkUpsert']);

    // Consultations
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/expert-systems/{expertSystemId}/diagnose', [ConsultationController::class, 'diagnose']);
    Route::get('/consultations/{id}', [ConsultationController::class, 'show']);
    Route::get('/expert-systems/{expertSystemId}/consultations/history', [ConsultationController::class, 'history']);
});

Route::get('/test', function() {
    return response()->json(['message' => 'API works!']);
});