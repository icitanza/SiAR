@extends('layouts.app')

@section('title')
    Laporan Surat
@endsection

@section('content')
    {{-- ada filter bulan, tahun, dan tipe --}}
    {{-- tambahkan pencarian --}}
    {{-- menampilkan setiap tujuan dan asal yang sama --}}
    {{-- jika surat masuk maka yang di group itu asal, jika surat keluar maka yang di group itu tujuan --}}
    {{-- tampilkan jumlah surat dalam group --}}
    {{-- dan tambahkan tombol detail yang menampilkan isi group tersebut --}}
    {{-- tambahkan tombol export excel dan pdf --}}

    <div class="card">
        <div class="card-body">
            <form method="GET" onchange="this.submit()" action="{{ route('letter.history') }}" class="d-flex justify-content-between flex-wrap gap-3 mb-3 align-items-end">
                <div class="d-flex col-md-7 flex-wrap gap-3">
                    <select name="filterTipe" class="form-control" style="max-width: 150px" >
                        <option value="">Semua Tipe</option>
                        <option value="masuk" {{ request('filterTipe') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ request('filterTipe') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                
                    <select name="filterBulan" class="form-control" style="max-width: 150px" >
                        <option value="">Semua Bulan</option>
                        @foreach (range(1, 12) as $bulan)
                            <option value="{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}"
                                {{ request('filterBulan') == str_pad($bulan, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                
                    <select name="filterTahun" class="form-control" style="max-width: 150px" >
                        <option value="">Semua Tahun</option>
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('filterTahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                
                <input type="text" placeholder="Cari..." value="{{ request('search') }}" class="form-control" style="max-width: 200px" name="search">
            </form>
            
            {{-- <div class="d-flex gap-5 mb-3">
                <select name="filterTipe" class="form-control" id="">
                    <option value="">Semua Tipe</option>
                    <option value="Surat Masuk">Surat Masuk</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                </select>
                <select name="filterBulan" class="form-control" id="">
                    <option value="">Semua Bulan</option>
                    <option value="-01-">Januari</option>
                    <option value="-02-">Februari</option>
                    <option value="-03-">Maret</option>
                    <option value="-04-">April</option>
                    <option value="-05-">Mei</option>
                    <option value="-06-">Juni</option>
                    <option value="-07-">Juli</option>
                    <option value="-08-">Agustus</option>
                    <option value="-09-">September</option>
                    <option value="-10-">Oktober</option>
                    <option value="-11-">November</option>
                    <option value="-12-">Desember</option>
                </select>
                <select name="filterTahun" class="form-control" id="">
                    <option value="">Semua Tahun</option>
                    @for ($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cari..." title="Type in a name">
            </div> --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="text-center" style="background-color: #696cff;  ">
                            <th style="border-top-left-radius: 8px;" class="text-white">Tipe</th>
                            <th class="text-white">No Surat</th>
                            <th class="text-white">Asal</th>
                            <th class="text-white">Tujuan</th>
                            <th class="text-white">Jumlah</th>
                            <th style="border-top-right-radius: 8px;" class="text-white ">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grouped as $key => $letter)
                            <tr>
                                <td>{!! $letter->first()->type == 'masuk' ? '<div class="badge bg-label-success">Surat Masuk</div>' : '<div class="badge bg-label-info">Surat Keluar</div>' !!}</td>
                                <td>
                                    {{ $letter->first()->no_letter }}
                                </td>
                                <td>
                                    {{ $letter->first()->type === 'masuk' ? $letter->first()->letter_from : 'SMKN 2 BANGKALAN' }}
                                </td>
                                <td>
                                    {{ $letter->first()->type === 'keluar' ? $letter->first()->letter_send_to : 'SMKN 2 BANGKALAN' }}
                                </td>
                                <td class="text-center">{{ $letter->count() }}</td>
                                <td class="text-center">
                                    <a href="{{ route('letter.history.detail', ['type' => $letter->first()->type, 'month' => request('filterBulan') ? request('filterBulan') : 'all', 'year' => request('filterTahun') ? request('filterTahun') : 'all', 'id' => $key]) }}" class="btn btn-info">Rincian</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- <div class="dataTables_wrapper">
                    {{ $pagination->links('pagination::bootstrap-5') }}
                </div> --}}
            </div>
        </div>
    </div>

    

@endsection