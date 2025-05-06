<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index() 
    {
        return view('scanQr');
    }
    public function processScan(Request $request)
    {
        // Ambil data QR Code dari input
        $qrCodeData = $request->input('qr_data');

        // Redirect ke URL yang terdapat dalam $qrCodeData
        return redirect()->away($qrCodeData); // Mengarahkan ke URL yang diambil dari QR Code
    }
}
