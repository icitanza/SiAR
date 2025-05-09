<?php

namespace App\DataTables;

use App\Models\Letter;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LettersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Letter> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->editColumn('letter_date', function ($user) {
            return date('d-m-Y | H:i', strtotime($user->letter_date));
        })
        ->editColumn('type', function ($user) {
            return $user->type == "masuk" 
                ? '<div class="text-center">
                    <a href="' . route('letter.detail', $user->type) . '" class="badge bg-label-success">Surat Masuk</a>
                   </div>' 
                : '<div class="text-center">
                    <a href="' . route('letter.detail', $user->type) . '" class="badge bg-label-info">Surat Keluar</a>
                   </div>';
        })
        ->addColumn('action', function ($user) {
            // Menambahkan kolom aksi dengan tombol edit dan delete.
            $editUrl = route('letter.form', $user->id);
            $deleteUrl = route('letter.destroy', $user->id);
            $fileURL = 'https://okqbhupontsalxjdbdyy.supabase.co/storage/v1/object/public/siar/upload/' . $user->letter_path;
            $csrf = csrf_field();
            $method = method_field('DELETE');

            return '
                <div class="d-flex justify-content-center gap-2">
                    <a href="' . $fileURL . '" target="_blank" class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                    <a href="' . $editUrl . '" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="' . $deleteUrl . '" id="deleteForm' . $user->id . '" method="POST">
                        ' . $csrf . '
                        ' . $method . '
                        <button type="button" onclick="confirmDelete('. $user->id .')" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>';
        })
        ->addColumn('qr', function ($user) {
            $qrData = asset('storage/' . $user->letter_path); // URL lengkap file PDF
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($qrData);
        
            return '<img src="' . $qrUrl . '" width="80" height="80" alt="QR Code">';
        })        
        ->rawColumns(['action', 'type', 'qr']) // Penting untuk memastikan HTML pada kolom aksi tidak di-escape.
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Letter>
     */
    public function query(Letter $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('letters-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(7, 'desc')
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])
                    ->parameters([
                        'dom' => '<"datatable-toolbar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3"B<"#custom-filter">f>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3"lp>',
                        'pageLength' =>10,
                        'buttons' => [
                            [
                                'text' => '<i class="fas fa-plus"></i> Tambah Surat',
                                'className' => 'btn btn-primary',
                                'action' => 'function ( e, dt, node, config ) {
                                    window.location.href = "' . route('letter.form') . '";
                                }',
                            ]
                        ],
                        'responsive' => true,
                        'autoWidth' => false,
                        'language' => [
                            'url' => asset('js/id.json'),
                            'searchPlaceholder' => 'Cari surat...',
                            'search' => '' // menghilangkan label "Search:"
                        ],
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('letter_name')->title('Nama'),
            Column::make('type')->title('Jenis'),
            Column::make('letter_date')->title('Tanggal')->addClass('text-end, text-nowrap'),
            Column::make('letter_from')->title('Asal'),
            Column::make('letter_send_to')->title('Tujuan'),
            Column::make('letter_subject')->title('Perihal'),
            Column::computed('qr')
                ->exportable(false)
                ->printable(false)
                ->title('QR Code')
                ->addClass('text-center'),
            Column::make('created_at')->visible(false),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->title('Aksi')
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Letters_' . date('YmdHis');
    }
}
