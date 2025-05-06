@extends('layouts.app')

@section('title')
    Scan Qr Code
@endsection

@section('content')
    <div id="reader" style="width: 100%; max-width: 500px; margin: auto;"></div>
    
    <form id="qrForm" method="POST" action="{{ route('qr.process') }}">
        @csrf
        <input type="hidden" name="qr_data" id="qrData">
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.7/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof Html5Qrcode === "undefined") {
                console.error("Html5Qrcode is not defined!");
                return;
            }

            const qrReader = new Html5Qrcode("reader");
            const qrForm = document.getElementById("qrForm");
            const qrDataInput = document.getElementById("qrData");

            const showLoading = () => {
                qrReader.stop(); // Menghentikan pemindaian QR code
                qrForm.style.display = 'none'; // Menyembunyikan form
                const loadingDiv = document.createElement('div');
                loadingDiv.innerText = 'Loading...';
                loadingDiv.style.position = 'fixed';
                loadingDiv.style.top = '0';
                loadingDiv.style.left = '0';
                loadingDiv.style.width = '100%';
                loadingDiv.style.height = '100%';
                loadingDiv.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
                loadingDiv.style.color = 'black';
                loadingDiv.style.display = 'flex';
                loadingDiv.style.alignItems = 'center';
                loadingDiv.style.justifyContent = 'center';
                loadingDiv.style.zIndex = '9999'; // Pastikan loading di atas elemen lain
                document.body.appendChild(loadingDiv);
            };

            qrReader.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 300, height: 300 },
                },
                (decodedText) => {
                    console.log(`QR Code detected: ${decodedText}`);
                    qrDataInput.value = decodedText;
                    showLoading(); // Menampilkan loading
                    qrForm.submit();
                },
                (errorMessage) => {
                    console.warn(`QR Code no match: ${errorMessage}`);
                }
            ).catch((err) => {
                console.error(`Unable to start scanning: ${err}`);
            });
        });
    </script>
@endsection
