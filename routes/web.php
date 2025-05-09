<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::group(['prefix' => 'letter', 'as' => 'letter.', 'middleware' => 'auth'], function () {
    Route::get('/', [LetterController::class, 'index'])->name('index');
    Route::get('/form/{id?}', [LetterController::class, 'form'])->name('form');
    Route::post('/store', [LetterController::class, 'store'])->name('store');
    Route::put('/update/{id}', [LetterController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [LetterController::class, 'destroy'])->name('destroy');
    Route::get('/history', [LetterController::class, 'history'])->name('history');
    Route::get('/detail/{type}', [LetterController::class, 'detail'])->name('detail');
    Route::get('/history/detail/{type}/{month}/{year}/{id}', [LetterController::class, 'historyDetail'])->name('history.detail');
});

Route::get('/qr/{id}', [LetterController::class, 'download'])->name('letter.download');
Route::get('/qr', [QrController::class, 'index'])->name('qr.index');
Route::post('/process-scan', [QrController::class, 'processScan'])->name('qr.process');


Route::get('/chart_data', [DashboardController::class, 'chartData'])->middleware(['auth', 'verified'])->name('chart_data_bulanan');


require __DIR__.'/auth.php';
