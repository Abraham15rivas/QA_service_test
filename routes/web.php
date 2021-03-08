<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Moderator\ModeratorController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/all/events', [HomeController::class, 'index'])->name('home');

Route::group([
    'middleware' => 'auth'
], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::group([
        'prefix' => 'moderator'
    ], function() {
        Route::get('/events', [ModeratorController::class, 'index'])->name('events');
        Route::post('/events/store', [ModeratorController::class, 'store'])->name('events.store');
        Route::post('/events/{event}/update', [ModeratorController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}/delete', [ModeratorController::class, 'destroy'])->name('events.destroy');
    });
});
