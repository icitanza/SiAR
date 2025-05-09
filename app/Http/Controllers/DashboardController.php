<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $minYear = DB::table('letters')->min(DB::raw('CAST(EXTRACT(YEAR FROM letter_date) AS INTEGER)'));
        $maxYear = DB::table('letters')->max(DB::raw('CAST(EXTRACT(YEAR FROM letter_date) AS INTEGER)'));
        return view('dashboard', compact(['minYear', 'maxYear']));
    }
    public function chartData(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $data = Letter::selectRaw('EXTRACT(MONTH FROM letter_date) as bulan, type, COUNT(*) as total')
            ->whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$tahun])
            ->groupBy('bulan', 'type')
            ->get();

        $suratMasuk = array_fill(1, 12, 0);
        $suratKeluar = array_fill(1, 12, 0);

        foreach ($data as $item) {
            $bulan = (int)$item->bulan; // pastikan bulan jadi integer untuk index array
            if ($item->type === 'masuk') {
                $suratMasuk[$bulan] = $item->total;
            } elseif ($item->type === 'keluar') {
                $suratKeluar[$bulan] = $item->total;
            }
        }

        $totalSemua = Letter::count();
        $totalTahunIni = Letter::whereRaw('EXTRACT(YEAR FROM letter_date) = ?', [$tahun])->count();

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
    }
}
