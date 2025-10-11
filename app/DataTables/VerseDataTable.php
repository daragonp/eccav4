<?php

namespace App\DataTables;

use App\Models\Verse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class VerseDataTable extends DataTable
{
    // Sugerencia: en App\Models\Verse añade:
    // protected $casts = ['date' => 'datetime']; // Para garantizar instancia Carbon [opcional]

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        try {
            return (new EloquentDataTable($query))
                // Fecha segura ante null y sin asumir Carbon si no hay cast
                ->editColumn('date', function ($row) {
                    // Si hay cast a datetime, $row->date será Carbon; si no, intenta parsear
                    if (empty($row->date)) {
                        return ''; // evita excepciones y mantiene la celda vacía
                    }
                    // optional() evita error si no es instancia; fallback a parseo
                    $dt = is_object($row->date) && method_exists($row->date, 'format')
                        ? $row->date
                        : \Carbon\Carbon::parse($row->date);
                    return $dt->format('d/m/Y');
                })
                ->addColumn('image', function ($imgquote) {
                    return $this->renderImageColumn($imgquote);
                })
                ->addColumn('video', function ($videoQuote) {
                    return $this->renderVideoColumn($videoQuote);
                })
                ->addColumn('audio', function ($audioQuote) {
                    return $this->renderAudioColumn($audioQuote);
                })
                ->addColumn('action', function ($quote) {
                    return $this->renderActionColumn($quote);
                })
                ->addColumn('status', function ($row) {
                    return $row->deleted_at
                        ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>'
                        : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
                })
                // Importante: marcar columnas HTML para evitar escape
                ->rawColumns(['image', 'video', 'audio', 'action', 'status']);
        } catch (\Throwable $e) {
            logger()->error('Error en VerseDataTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    protected function renderActionColumn($quote)
    {
        $html = view('admin.partials.actions', [
            'id'         => $quote->id,
            'view'       => url("view-quote", $quote->id),
            'activate'   => url("activate-quote", $quote->id),
            'softdelete' => url("delete-quote", $quote->id),
            'realdelete' => url("realdelete-quote", $quote->id),
            'modalId'    => 'EditModal_'.$quote->id,
            'formAction' => url("update-quote", $quote->id),
            'tableM'     => $quote,
            'editPartial'=> 'admin.quote.editmodal',
        ])->render();

        return $html ?? '';
    }

    protected function renderImageColumn($imgquote)
    {
        if (empty($imgquote->image)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-image-slash"></i> Sin imagen</span>';
        }
        $url = asset("images/bible/{$imgquote->image}");

        $html = view('admin.partials.media', [
            'url'  => $url,
            'type' => 'image',
            'alt'  => $imgquote->text ?? 'imagen',
        ])->render();

        return $html ?? '';
    }

    protected function renderVideoColumn($videoQuote)
    {
        if (empty($videoQuote->video)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-file-pdf"></i> Sin documento</span>';
        }
        $url = asset("documents/quote/{$videoQuote->video}");

        return '<a href="' . e($url) . '" target="_blank" class="btn btn-sm btn-ghost text-red-600"><i class="fas fa-file-pdf me-1"></i> Ver PDF</a>';
    }

    protected function renderAudioColumn($audioQuote)
    {
        if (empty($audioQuote->audio)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-volume-mute"></i> Sin audio</span>';
        }
        $url = asset("audio/quote/{$audioQuote->audio}");

        $html = view('admin.partials.media', [
            'url'  => $url,
            'type' => 'audio',
        ])->render();

        return $html ?? '';
    }

    public function query(Verse $model): QueryBuilder
    {
        try {
            if (Auth::check()) {
                if ((Auth::user()->roles->pluck('id')[0] ?? null) === 1) {
                    return $model->newQuery();
                }
                return $model->newQuery()->whereNull('deleted_at');
            }
            return $model->newQuery()->whereNull('deleted_at');
        } catch (\Throwable $e) {
            logger()->error('Error en query de VerseDataTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('verse-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // Si ‘date’ es columna real, este orderBy(0,'desc') sirve; si no, mapear con orderColumn
            ->orderBy(0, 'desc')
            ->parameters([
                'responsive' => true,
                'language' => [
                    'url' => asset('js/datatables-es.json')
                ],
                'processing' => true,
                'serverSide' => true,
                'autoWidth' => false,
                'pageLength' => 10,
                'dom' => '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                'initComplete' => 'function() { $("#verse-table_length select, #verse-table_filter input").addClass("form-control"); }',
                // Opcional: desactivar orden/búsqueda en computadas si no hay mapeo
                'columnDefs' => [
                    ['orderable' => false, 'searchable' => false, 'targets' => [1,2,3,4,5]]
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            // Asegurar que el data/name coincide con la clave producida por editColumn/addColumn
            Column::make('date')->title('Fecha')->width(120),
            Column::make('image')->title('Imagen')->width(120)->addClass('text-center'),
            Column::make('video')->title('Documento')->width(120),
            Column::make('audio')->title('Audio')->width(120),
            Column::make('status')->title('Estado')->width(120),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->width(180),
        ];
    }

    protected function filename(): string
    {
        return 'Verse_' . date('YmdHis');
    }
}
