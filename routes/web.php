<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\PhotoController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'storeRegister'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Agenda Routes
Route::middleware('auth')->group(function () {
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/api/agenda/{agenda}/documents', [AgendaController::class, 'getDocumentsByType'])->name('api.agenda.documents');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/agenda/create', [AgendaController::class, 'create'])->name('agenda.create');
    Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{agenda}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{agenda}', [AgendaController::class, 'update'])->name('agenda.update');
    Route::delete('/agenda/{agenda}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/agenda/{agenda}', [AgendaController::class, 'show'])->name('agenda.show');
});

// Document Routes (replacing Folder Routes)
Route::middleware('auth')->group(function () {
    Route::get('/document', [DocumentController::class, 'index'])->name('document.index');
    Route::get('/document/export/excel', [DocumentController::class, 'exportExcel'])->name('document.export.excel');
    Route::get('/document/create', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/document', [DocumentController::class, 'store'])->name('document.store');
    Route::post('/document/{document}/verification', [DocumentController::class, 'saveVerification'])->name('document.verify');
    Route::post('/document/{document}/sp2d', [DocumentController::class, 'saveSp2d'])->name('document.sp2d');
    Route::get('/document/{document}', [DocumentController::class, 'show'])->name('document.show');
    Route::delete('/document/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('document.download');
});

// Document Approval Routes (Admin Only)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('document.approve');
    Route::get('/documents/{document}/reject', [DocumentController::class, 'showRejectForm'])->name('document.reject-form');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('document.reject');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pending-approvals', [ApprovalController::class, 'pending'])->name('pending');
    Route::get('/approval-history', [ApprovalController::class, 'history'])->name('history');
    Route::post('/document/{document}/approve', [DocumentController::class, 'approve'])->name('approve');
    Route::post('/document/{document}/reject', [DocumentController::class, 'reject'])->name('reject');
});

// RAB Routes
Route::middleware('auth')->resource('rab', RabController::class);

// RAB Upload untuk Agenda (khusus upload ke agenda)
Route::middleware('auth')->group(function () {
    Route::get('/agenda/{agenda}/rab/create', [RabController::class, 'createForAgenda'])->name('rab.create-for-agenda');
    Route::post('/agenda/{agenda}/rab', [RabController::class, 'storeForAgenda'])->name('rab.store-for-agenda');
});

// Photo Routes (Admin only for upload/delete)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/agenda/{agenda}/photo', [PhotoController::class, 'store'])->name('photo.store');
    Route::delete('/photo/{photo}', [PhotoController::class, 'destroy'])->name('photo.destroy');
});

// Photo serving route (public, anyone can view)
Route::get('/photo/{photo}/view', [PhotoController::class, 'show'])->name('photo.show');

