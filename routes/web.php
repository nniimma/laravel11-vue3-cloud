<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/files/{folder?}', [FileController::class, 'index'])
        ->where('folder', '(.*)')->name('files.index');
    Route::post('/folders', [FileController::class, 'folderStore'])->name('folders.store');
    Route::post('/files', [FileController::class, 'fileStore'])->name('files.store');
    Route::delete('/files', [FileController::class, 'destroy'])->name('files.destroy');
    Route::delete('/files/delete', [FileController::class, 'destroyPermanently'])->name('files.destroyPermanently');
    Route::put('/files', [FileController::class, 'restore'])->name('files.restore');
    Route::get('/trash', [FileController::class, 'trash'])->name('trash');
    Route::post('/favorites', [FileController::class, 'addToFavorites'])->name('files.favorites');
    Route::post('/files/share', [FileController::class, 'share'])->name('files.share');
    Route::get('/shared-with-me', [FileController::class, 'sharedWithMe'])->name('files.sharedWithMe');
    Route::get('/shared-by-me', [FileController::class, 'sharedByMe'])->name('files.sharedByMe');
    Route::get('/file/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/file/download-shared-with-me', [FileController::class, 'downloadSharedWithMe'])->name('files.downloadSharedWithMe');
    Route::get('/file/download-shared-with-me', [FileController::class, 'downloadSharedByMe'])->name('files.downloadSharedByMe');
});

require __DIR__ . '/auth.php';
