@extends('layouts.app')

@section('title')
    Surat
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                $('#custom-filter').html(`
                    <div class="d-flex gap-2 align-items-center">
                        <select id="filterBulan" class="select2 form-select" style="max-width: 150px;">
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

                        <select id="filterTahun" class="select2 form-select" style="max-width: 150px;">
                            <option value="">Semua Tahun</option>
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                `);

                function applyFilter() {
                    const bulan = $('#filterBulan').val();
                    const tahun = $('#filterTahun').val();
                    const regex = tahun && bulan ? `${tahun}${bulan}` :
                                  tahun ? `^${tahun}` :
                                  bulan ? `${bulan}` : '';

                    $('#letters-table').DataTable().column(2).search(regex, true, false).draw();
                }

                $('#filterBulan, #filterTahun').on('change', applyFilter);
            }, 500);
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                $('#custom-filter').html(`
                    <div class="d-flex gap-2 align-items-center">
                        <select id="filterTipe" class="select2 form-select" style="max-width: 150px;">
                            <option value="">Semua Tipe</option>
                            <option value="masuk">Surat Masuk</option>
                            <option value="keluar">Surat Keluar</option>
                        </select>
    
                        <select id="filterBulan" class="select2 form-select" style="max-width: 150px;">
                            <option value="">Semua Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
    
                        <select id="filterTahun" class="select2 form-select" style="max-width: 150px;">
                            <option value="">Semua Tahun</option>
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                `);
    
                const table = $('#letters-table').DataTable();
    
                function applyFilter() {
                    const tipe = $('#filterTipe').val();
                    const bulan = $('#filterBulan').val();
                    const tahun = $('#filterTahun').val();
    
                    // Reset semua pencarian kolom
                    table.columns().search('');
    
                    if (tipe) {
                        table.column(1).search(tipe); // Pastikan kolom 1 adalah kolom "tipe"
                    }
    
                    if (tahun || bulan) {
                        const regex = tahun && bulan
                            ? `^${tahun}-${bulan}`     // Format YYYY-MM
                            : tahun
                            ? `^${tahun}`
                            : `-${bulan}-`;            // Format -MM- untuk bulan saja
    
                        table.column(2).search(regex, true, false); // Pastikan kolom 2 adalah kolom tanggal
                    }
    
                    table.draw();
                }
    
                $('#filterTipe, #filterBulan, #filterTahun').on('change', applyFilter);
            }, 500);
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm' + id);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form tidak ditemukan untuk ID:', id);
                    }
                }
            });
        }
    </script>
    
@endpush