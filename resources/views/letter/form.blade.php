@extends('layouts.app')

@section('title')
    {{ request()->route('id') ? 'Edit' : 'Tambah' }} Surat
@endsection

@section('content')
    @php
        $url = '';
        if (request()->route('id')) {
            $url = route('letter.update', ['id' => $data->id]);
        } else {
            $url = route('letter.store');
        }
    @endphp

    <div class="card">
        <div class="card-body">
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (request()->route('id'))
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="letter_name" class="form-label">NAMA</label>
                            <input type="text" name="letter_name" class="form-control" value="{{ $data->letter_name ?? '' }}"  placeholder="Nama surat" >
                            @error('letter_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="letter_date" class="form-label">TANGGAL</label>
                            <input type="datetime-local" name="letter_date" class="form-control" value="{{ $data->letter_date ?? '' }}" >
                            @error('letter_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">JENIS</label>
                            <select name="type" class="select2 form-control" id="type" onchange="toggleLetterType()">
                                <option></option>
                                <option value="masuk" {{ isset($data) && $data->type == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                <option value="keluar" {{ isset($data) && $data->type == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
        
                    <div class="col-md-6">
                        <div class="mb-3" id="asalSurat" style="display: none">
                            <label for="letter_from" class="form-label">ASAL</label>
                            <input type="text" name="letter_from" class="form-control" value="{{ $data->letter_from ?? '' }}" placeholder="Asal surat">
                            @error('letter_from')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="tujuanSurat" style="display: none">
                            <label for="letter_send_to" class="form-label">TUJUAN</label>
                            <input type="text" name="letter_send_to" class="form-control" value="{{ $data->letter_send_to ?? '' }}" placeholder="Tujuan surat">
                            @error('letter_send_to')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="letter_subject" class="form-label">PERIHAL</label>
                            <input type="text" name="letter_subject" class="form-control" value="{{ $data->letter_subject ?? '' }}"  placeholder="Perihal surat">
                            @error('letter_subject')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">FILE SURAT <sup class="text-danger fw-bold">*PDF</sup></label>
                            <input type="file" name="file" class="form-control">
                            @error('file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="button" onclick="history.back()" class="btn btn-secondary px-4">Kembali</button>
                    <button type="submit" class="btn btn-primary px-4">
                        {{ request()->route('id') ? 'Perbarui' : 'Tambah' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleLetterType() {
            var type = document.getElementById('type').value;
            var asalSurat = document.getElementById('asalSurat');
            var tujuanSurat = document.getElementById('tujuanSurat');
            var letterFromInput = document.querySelector('input[name="letter_from"]');
            var letterSendToInput = document.querySelector('input[name="letter_send_to"]');

            if (type === 'masuk') {
                asalSurat.style.display = 'block';
                tujuanSurat.style.display = 'none';
                if (letterSendToInput && letterSendToInput.value.trim() === '') {
                    letterSendToInput.value = 'SMKN 2 BANGKALAN';
                }
            } else {
                asalSurat.style.display = 'none';
                tujuanSurat.style.display = 'block';
                if (letterFromInput && letterFromInput.value.trim() === '') {
                    letterFromInput.value = 'SMKN 2 BANGKALAN';
                }
            }
        }

        window.addEventListener('DOMContentLoaded', (event) => {
            toggleLetterType(); // jalankan fungsi saat halaman dimuat
        });
    </script>
@endpush