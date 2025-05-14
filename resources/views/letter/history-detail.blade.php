@extends('layouts.app')

@section('title')
    @php
        $text = request()->route('type') == 'masuk' ? 'Surat dari' : 'Surat ke';
        $result = $text . " " . $title
    @endphp
    {{ $result }}
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
            <button type="button" onclick="history.back()" class="btn btn-secondary px-4 mb-3"><i class="fas fa-arrow-left"></i> Kembali</button>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="text-center" style="background-color: #696cff;">
                            <th style="width: 5%; border-top-left-radius: 8px" class="text-white">Nama</th>
                            {{-- <th class="text-white">Nama</th> --}}
                            <th class="text-white">No Surat</th>
                            <th class="text-white">Tipe</th>
                            <th class="text-white">Asal</th>
                            <th class="text-white">Tujuan</th>
                            <th class="text-white">Tanggal</th>
                            <th style="border-top-right-radius: 8px" class="text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                        <tr>
                            {{-- <td class="text-center">{{ $loop->iteration }}</td> --}}
                            <td>{{ $item->letter_name }}</td>
                            <td>{{ $item->no_letter }}</td>
                            <td>{!! $item->type == 'masuk' ? '<div class="badge bg-label-success">Surat Masuk</div>' : '<div class="badge bg-label-info">Surat Keluar</div>' !!}</td>
                            <td>
                                {{ $item->letter_from }}
                            </td>
                            <td>
                                {{ $item->letter_send_to }}
                            </td>
                            <td class="text-end text-nowrap">
                                {{ date('d-m-Y | H:i', strtotime($item->letter_date)) }}
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