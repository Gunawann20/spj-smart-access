<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk mendapatkan dokumen per jenis di agenda
Route::get('/agenda/{agenda}/documents', [AgendaController::class, 'getDocumentsByType'])
    ->middleware('auth')
    ->name('api.agenda.documents');
