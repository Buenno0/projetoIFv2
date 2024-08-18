<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/', function () {
    return view('index');
});


