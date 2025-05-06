<?php

use App\Http\Controllers\LetterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $minYear = DB::table('letters')->min(DB::raw('YEAR(letter_date)'));
    $maxYear = DB::table('letters')->max(DB::raw('YEAR(letter_date)'));
    return view('dashboard', compact(['minYear', 'maxYear']));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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


Route::get('/api/chart_data_bulanan', function (Request $request) {
    $tahun = $request->get('tahun', now()->year); // default ke tahun sekarang jika tidak dikirim

    $data = Letter::selectRaw('MONTH(letter_date) as bulan, type, COUNT(*) as total')
        ->whereYear('letter_date', $tahun)
        ->groupBy('bulan', 'type')
        ->get();

    $suratMasuk = array_fill(1, 12, 0);
    $suratKeluar = array_fill(1, 12, 0);

    foreach ($data as $item) {
        if ($item->type === 'masuk') {
            $suratMasuk[$item->bulan] = $item->total;
        } elseif ($item->type === 'keluar') {
            $suratKeluar[$item->bulan] = $item->total;
        }
    }
    $totalSemua = Letter::count();
    $totalTahunIni = Letter::whereYear('letter_date', $tahun)->count();

    return response()->json([
        'labels' => [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ],
        'series' => [
            [
                'name' => 'Surat Masuk',
                'data' => array_values($suratMasuk)
            ],
            [
                'name' => 'Surat Keluar',
                'data' => array_values($suratKeluar)
            ]
        ],
        'totalMasuk' => Letter::where('type', 'masuk')->count(),
        'totalKeluar' => Letter::where('type', 'keluar')->count(),
        'tahun' => $tahun,
        'debug' => [
            'total_semua' => $totalSemua,
            'total_tahun_ini' => $totalTahunIni,
        ]
    ]);
})->middleware(['auth', 'verified'])->name('chart_data_bulanan');

require __DIR__.'/auth.php';
