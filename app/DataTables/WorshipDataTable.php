<?php

namespace App\DataTables;

use App\Models\Worship;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class WorshipDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        try {
            return (new EloquentDataTable($query))
                ->editColumn('broadcast', function ($row) {
                    // Si hay cast a datetime, $row->broadcast será Carbon; si no, intenta parsear
                    if (empty($row->broadcast)) {
                        return ''; // evita excepciones y mantiene la celda vacía
                    }
                    // optional() evita error si no es instancia; fallback a parseo
                    $dt = is_object($row->broadcast) && method_exists($row->broadcast, 'format')
                        ? $row->broadcast
                        : \Carbon\Carbon::parse($row->broadcast);
                    return $dt->format('d/m/Y');
                })
                ->addColumn('image', function ($imgworship) {
                    return $this->renderImageColumn($imgworship);
                })
                ->addColumn('audio', function ($audioWorship) {
                    return $this->renderAudioColumn($audioWorship);
                })
                ->addColumn('action', function ($worship) {
                    return $this->renderActionColumn($worship);
                })
                ->addColumn('status', function ($row) {
                    return $row->deleted_at
                        ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>'
                        : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
                })
                ->addColumn('ai_status', function ($row) {
                    if (!$row->audio) {
                        return '<span class="chip-brand bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 border-gray-300 dark:border-gray-700"><i class="fas fa-volume-mute mr-1"></i> Sin audio</span>';
                    }
                    
                    return $row->ai_processed
                        ? '<span class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700"><i class="fas fa-robot mr-1"></i> Procesado con IA</span>'
                        : '<span class="chip-brand bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border-yellow-300 dark:border-yellow-700"><i class="fas fa-clock mr-1"></i> Pendiente de IA</span>';
                })
                // Importante: marcar columnas HTML para evitar escape
                ->rawColumns(['image', 'audio', 'action', 'status', 'ai_status']);
        } catch (\Throwable $e) {
            logger()->error('Error en WorshipDataTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    protected function renderActionColumn($worship)
    {
        $html = view('admin.partials.actions', [
            'id'         => $worship->id,
            'view'       => url("view-worship", $worship->id),
            'activate'   => url("activate-worship", $worship->id),
            'softdelete' => url("delete-worship", $worship->id),
            'realdelete' => url("realdelete-worship", $worship->id),
            'reprocess'  => url("reprocess-worship-ai", $worship->id),
            'modalId'    => 'EditModal_'.$worship->id,
            'formAction' => url("update-worship", $worship->id),
            'tableM'     => $worship,
            'editPartial'=> 'admin.worship.editmodal',
        ])->render();

        return $html ?? '';
    }

    protected function renderImageColumn($imgworship)
    {
        if (empty($imgworship->image)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-image-slash"></i> Sin imagen</span>';
        }
        $url = asset("images/worship/{$imgworship->image}");

        $html = view('admin.partials.media', [
            'url'  => $url,
            'type' => 'image',
            'alt'  => $imgworship->title ?? 'imagen',
        ])->render();

        return $html ?? '';
    }

    protected function renderAudioColumn($audioWorship)
    {
        if (empty($audioWorship->audio)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-volume-mute"></i> Sin audio</span>';
        }
        $url = asset("audio/worship/{$audioWorship->audio}");

        $html = view('admin.partials.media', [
            'url'  => $url,
            'type' => 'audio',
        ])->render();

        return $html ?? '';
    }

    public function query(Worship $model): QueryBuilder
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
            logger()->error('Error en query de WorshipDataTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('worship-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // Si 'broadcast' es columna real, este orderBy(0,'desc') sirve; si no, mapear con orderColumn
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
                'initComplete' => 'function() { $("#worship-table_length select, #worship-table_filter input").addClass("form-control"); }',
                // Opcional: desactivar orden/búsqueda en computadas si no hay mapeo
                'columnDefs' => [
                    ['orderable' => false, 'searchable' => false, 'targets' => [3,4,5,6]]
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            // Asegurar que el data/name coincide con la clave producida por editColumn/addColumn
            Column::make('title')->title('Título'),
            Column::make('broadcast')->title('Fecha')->width(120),
            Column::make('autor')->title('Autor'),
            Column::make('badge')->title('Etiqueta'),
            Column::make('image')->title('Imagen')->width(120)->addClass('text-center'),
            Column::make('audio')->title('Audio')->width(120),
            Column::make('ai_status')->title('Estado IA')->width(120),
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
        return 'Worship_' . date('YmdHis');
    }
}