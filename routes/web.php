<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
