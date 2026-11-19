<?php

use App\Events\LiveDraw;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'all']);
Route::get('/all', [GameController::class, 'all']);

Route::get('/results', [GameController::class, 'all'])->name('results');
Route::get('/result/{id}', [GameController::class, 'one'])->name('result.show');
Route::get('/download/{id}', [GameController::class, 'download'])->name('result.download');
Route::get('/sitemap', [GameController::class, 'map'])->name('sitemap');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/live-draw', [GameController::class, 'index'])->name('board');
    Route::post('/result-generate', [GameController::class, 'create'])->name('result.generate');
    
    Route::get('/report', [GameController::class, 'report'])->name('report');
    Route::post('/replay', [GameController::class, 'replay'])->name('replay');
    Route::post('/unpublish', [GameController::class, 'unpublish'])->name('unpublish');
    
    Route::post('/publish', [GameController::class, 'publish'])->name('publish');
    Route::post('/delete', [GameController::class, 'delete'])->name('delete.result');
    Route::get('/get-result/{game}', [GameController::class, 'show'])->name('get.result');
});

require __DIR__.'/auth.php';
