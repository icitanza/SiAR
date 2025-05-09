@extends('layouts.app')

@section('title')
    Surat
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('letter.form') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
            <form method="GET" onchange="this.submit()" action="{{ route('letter.index') }}" class="d-flex justify-content-between flex-wrap gap-3 mb-3 align-items-end">
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
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="text-center" style="background-color: #696cff;">
                            <th style="width: 5%; border-top-left-radius: 8px" class="text-white">Nama</th>
                            <th class="text-white">Tipe</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Asal</th>
                            <th class="text-white">Tujuan</th>
                            <th class="text-white">Perihal</th>
                            <th class="text-white">QR</th>
                            <th style="border-top-right-radius: 8px" class="text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                        <tr>
                            {{-- <td class="text-center">{{ $loop->iteration }}</td> --}}
                            <td>{{ $item->letter_name }}</td>
                            <td>{!! $item->type == 'masuk' ? '<div class="badge bg-label-success">Surat Masuk</div>' : '<div class="badge bg-label-info">Surat Keluar</div>' !!}</td>
                            <td class="text-end text-nowrap">
                                {{ date('d-m-Y | H:i', strtotime($item->letter_date)) }}
                            </td>
                            <td>
                                {{ $item->letter_from }}
                            </td>
                            <td>
                                {{ $item->letter_send_to }}
                            </td>
                            <td>{{ $item->letter_subject }}</td>
                            <td class="text-center p-0">
                                @if($item->link_qr)
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($item->link_qr) }}" 
                                         width="120" height="120" 
                                         alt="QR Code"
                                         class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada QR</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href={{ 'https://okqbhupontsalxjdbdyy.supabase.co/storage/v1/object/public/siar/upload/' . $item->letter_path }} target="_blank" class="btn btn-info">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href={{ route('letter.form', $item->id) }} class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action={{ route('letter.destroy', $item->id) }} method="POST" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus pengguna ini?\');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <td colspan="3">Tidak ada data</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
