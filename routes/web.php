<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group([
    'middleware' => 'auth'
], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
