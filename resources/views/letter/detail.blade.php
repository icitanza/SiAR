@extends('layouts.app')

@section('title')
    {{ request()->route('type') == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
@endsection

@section('content')
    @php
        $type = request()->route('type');
    @endphp
    <div class="card">
        <div class="card-body">
            <button type="button" onclick="history.back()" class="btn btn-secondary px-4 mb-3"><i class="fas fa-arrow-left"></i> Kembali</button>

            <form method="GET" onchange="this.submit()" action="{{ route('letter.detail', $type) }}" class="d-flex justify-content-between flex-wrap gap-3 mb-3 align-items-end">
                <div class="d-flex col-md-7 flex-wrap gap-3">
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
                        <tr class="text-center" style="background-color: #696cff;  ">
                            <th style="border-top-left-radius: 8px; width: 5%;" class="text-white">No</th>
                            <th class="text-white">Nama</th>
                            <th class="text-white">No Surat</th>
                            <th class="text-white">Tanggal</th>
                            {!! $type == 'masuk' ? '<th class="text-white">Asal</th>' : '<th class="text-white">Tujuan</th>' !!}
                            {{-- <th class="text-white">Asal</th>
                            <th class="text-white">Tujuan</th> --}}
                            <th class="text-white">Perihal</th>
                            <th style="border-top-right-radius: 8px;" class="text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $letter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $letter->letter_name }}</td>
                                <td>{{ $letter->no_letter }}</td>
                                <td class="text-end text-nowrap">{{ date('d-m-Y | H:i', strtotime($letter->letter_date)) }}</td>
                                {!! $type == 'masuk' ? '<td>' . $letter->letter_from . '</td>' : '<td>' . $letter->letter_send_to . '</td>' !!}
                                {{-- <td>{{ $letter->letter_from }}</td>
                                <td>{{ $letter->letter_send_to }}</td> --}}
                                <td>{{ $letter->letter_subject }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href={{ 'https://okqbhupontsalxjdbdyy.supabase.co/storage/v1/object/public/siar/upload/' . $letter->letter_path }} target="_blank" class="btn btn-info">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href={{ route('letter.form', $letter->id) }} class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action={{ route('letter.destroy', $letter->id) }} method="POST" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus pengguna ini?\');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="dataTables_wrapper">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection