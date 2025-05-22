<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekognitionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rotas públicas
Route::get('/', [DashboardController::class, 'landing'])->name('landing');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');
// Rotas de autenticação (fornecidas pelo Breeze)
require __DIR__.'/auth.php';

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // // Dashboard
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    //dashboard redirect to search
    Route::get('/dashboard', function () {
        return redirect()->route('search.form');
    })->name('dashboard');
    // Busca por selfie (todos os usuários)
    Route::get('/search', [RekognitionController::class, 'showSearchForm'])->name('search.form');
    Route::post('/search', [RekognitionController::class, 'searchBySelfie'])->name('search.submit');
    
    // Resultados e download
    Route::get('/results', [RekognitionController::class, 'showResults'])->name('results');
    Route::get('/download/{key}', [RekognitionController::class, 'downloadImage'])->name('download.image');
    Route::post('/download-multiple', [RekognitionController::class, 'downloadMultiple'])->name('download.multiple');

    
    // Galeria de fotos
    Route::get('/gallery', [RekognitionController::class, 'showGallery'])->name('gallery');
    
    // Rotas apenas para administradores
    Route::middleware(['admin'])->group(function () {
        // Upload de fotos
        Route::get('/upload', [RekognitionController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload', [RekognitionController::class, 'uploadImages'])->name('upload.submit');
        Route::get('/delete-all-faces', [RekognitionController::class, 'deleteAllFaces']);

    });


    //rota direto para a view terms 
   
});
