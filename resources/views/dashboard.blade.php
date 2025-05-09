@extends('layouts.app')

@section('title')
    Beranda
@endsection

@section('content')
    <div class="row">
        <!-- Surat Masuk -->
        <div class="col-lg-6 col-md-6 col-12 mb-3 " data-aos="fade-right">
            <a href="{{ route('letter.detail', ['type' => 'masuk']) }}" class="box-dashboard">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Surat Masuk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="masuk-count">0</div>
                            </div>
                            <div>
                                <i class="fas fa-fw fa-solid fa-right-to-bracket fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Surat Keluar -->
        <div class="col-lg-6 col-md-6 col-12 mb-3 " data-aos="fade-left">
            <a href="{{ route('letter.detail', ['type' => 'keluar']) }}" class="box-dashboard">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Surat Keluar
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="keluar-count">0</div>
                            </div>
                            <div>
                                <i class="fas fa-fw fa-solid fa-right-from-bracket fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div><br>


    <div class="card shadow">
        <div class="card-body">
            <select id="tahun" name="tahun" class="select2 form-select">
                @for ($year = $minYear; $year <= $maxYear; $year++)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
            
            <div id="chart" class="mt-4"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
    let chart; // Dideklarasikan di luar agar bisa diakses global

    function loadChart(tahun) {
        fetch(`/chart_data?tahun=${tahun}`)
            .then(res => res.json())
            .then(data => {

                document.getElementById('masuk-count').textContent = data.totalMasuk;
                document.getElementById('keluar-count').textContent = data.totalKeluar;

                const options = {
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    series: data.series,
                    title: {
                        text: `Grafik Surat Masuk dan Keluar Tahun ${tahun}`,
                        align: 'center'
                    },
                    xaxis: {
                        categories: data.labels
                    }
                };

                if (chart) {
                    chart.updateOptions({
                        series: data.series,
                        title: {
                            text: `Grafik Surat Masuk dan Keluar Tahun ${tahun}`,
                            align: 'center'
                        },
                        xaxis: {
                            categories: data.labels
                        }
                    });
                } else {
                    chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }
            });
    }

    // Event listener dropdown
    // document.getElementById('tahun').addEventListener('change', function () {
    //     const tahun = this.value;
    //     console.log('tahun', tahun);
        
    //     loadChart(tahun);
    // });
    $(document).ready(function () {
        // Event listener Select2 pakai jQuery
        $('#tahun').on('change', function () {
            const tahun = $(this).val();
            console.log('tahun', tahun);
            loadChart(tahun);
        });

        // Muat data awal
        const defaultTahun = $('#tahun').val();
        loadChart(defaultTahun);
    });

    // Muat data awal
    document.addEventListener('DOMContentLoaded', function () {
        const defaultTahun = document.getElementById('tahun').value;
        loadChart(defaultTahun);
    });
    </script>
@endsection
