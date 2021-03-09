<?php
// Importaciones
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Moderator\ModeratorController;
// Authentication
Auth::routes();
// Rutas de guest
Route::get('/', [HomeController::class, 'show']);
Route::get('/event', [HomeController::class, 'show']);
Route::get('/question-answer', [HomeController::class, 'show']);
Route::get('/report', [HomeController::class, 'show']);
Route::get('/my-question', [HomeController::class, 'show']);
Route::get('/event/{event}', [HomeController::class, 'show']);
Route::get('/all/events', [HomeController::class, 'index']);
Route::get('/questions/all', [HomeController::class, 'allQuestions']);
Route::get('/questions/approved/{event}', [HomeController::class, 'allApproved']);
Route::get('/questions/deneid', [HomeController::class, 'allDenied']);
Route::post('/question/new', [HomeController::class, 'setQuestion']);
// Grupo de rutas de usuarios logeados
Route::group([
    'middleware' => 'auth'
], function() {
    // Rutas del participante
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Grupo de rutas del moderador
    Route::group([
        'prefix' => 'moderator'
    ], function() {
        Route::get('/events', [ModeratorController::class, 'index']);
        Route::post('/events/store', [ModeratorController::class, 'store']);
        Route::post('/events/{event}/update', [ModeratorController::class, 'update']);
        Route::delete('/events/{event}/delete', [ModeratorController::class, 'destroy']);
    });
});
