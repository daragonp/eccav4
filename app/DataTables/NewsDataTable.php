<?php

namespace App\DataTables;

use App\Models\News;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;

class NewsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // Si 'autor' viene de relación o atributo, puede editarse aquí para asegurar consistencia
            ->editColumn('autor', function ($row) {
                // Ajusta según tu modelo: si hay relación user, por ejemplo:
                // return optional($row->user)->name ?? ($row->autor ?? '');
                return $row->autor ?? '';
            })
            ->addColumn('image', function ($row) {
                return $this->renderImageColumn($row);
            })
            ->addColumn('pdfdoc', function ($row) {
                return $this->renderPdfColumn($row);
            })
            ->addColumn('audio', function ($row) {
                return $this->renderAudioColumn($row);
            })
            ->addColumn('action', function ($row) {
                return $this->renderActionColumn($row);
            })
            ->addColumn('status', function ($row) {
                return $row->deleted_at
                    ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>'
                    : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Publicado</span>';
            })
            // Importante para que no se escape el HTML de las columnas personalizadas
            ->rawColumns(['image', 'pdfdoc', 'audio', 'action', 'status']);
    }

    protected function renderActionColumn($news)
    {
        $html = view('admin.partials.actions', [
            'id'           => $news->id,
            'view'         => url("view-news", $news->id),
            'activate'     => url("activate-news", $news->id),
            'softdelete'   => url("delete-news", $news->id),
            'realdelete'   => url("realdelete-news", $news->id),
            'modalId'      => 'EditModal_' . $news->id,
            'formAction'   => url("update-news", $news->id),
            'tableM'       => $news,
            'sectionType'  => 'news',
            'sectionTitle' => 'Mensaje',
        ])->render();

        return $html ?? '';
    }

    protected function renderImageColumn($news)
    {
        if (empty($news->image)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-image-slash"></i> Sin imagen</span>';
        }
        $url = asset("images/news/{$news->image}");
        $html = view('admin.partials.media', [
            'url'  => $url,
            'type' => 'image',
            'alt'  => $news->title ?? 'imagen',
        ])->render();

        return $html ?? '';
    }

    protected function renderPdfColumn($news)
    {
        if (empty($news->pdfdoc)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-file-pdf"></i> Sin PDF</span>';
        }
        $url = asset("documents/news/{$news->pdfdoc}");
        return '<a href="' . e($url) . '" target="_blank" class="btn btn-sm btn-ghost text-red-600"><i class="fas fa-file-pdf me-1"></i> Ver PDF</a>';
    }

    protected function renderAudioColumn($news)
    {
        if (empty($news->audio)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-volume-mute"></i> Sin audio</span>';
        }
        $url = asset("audio/news/{$news->audio}");
        return '<audio controls class="w-full max-w-xs"><source src="' . e($url) . '" type="audio/mpeg">Tu navegador no soporta este elemento de audio.</audio>';
    }

    // Reemplaza el método query en NewsDataTable
    public function query(News $model): \Illuminate\Database\Eloquent\Builder
    {
        $q = $model->newQuery()->latest();

        // Opción A: si hay campo role_id==1 para admin
        if (\Illuminate\Support\Facades\Auth::check() && ((\Illuminate\Support\Facades\Auth::user()->role_id ?? null) === 1)) {
            return $q->where('category', 1);
        }

        // Opción B: si usas Spatie pero quieres evitar el warning del editor,
        // usa Gate/Policy que Intelephense sí puede resolver por string:
        // if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Gate::allows('view-admin-news')) {
        //     return $q->where('category', 1);
        // }

        return $q->whereNull('deleted_at');
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('news-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // Orden inicial por primera columna (title). Cambia a índice de created_at si la añades.
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
                'initComplete' => 'function() { $("#news-table_length select, #news-table_filter input").addClass("form-control"); }',
            ]);
    }

    public function getColumns(): array
    {
        return [
            // Asegura que 'title' y 'autor' existen en el JSON (title viene del modelo; autor se normaliza con editColumn)
            Column::make('title')->title('Título')->width(250),
            Column::make('autor')->title('Autor')->width(160),
            // Columnas computadas: no ordenables/buscables a menos que se mapeen con orderColumn/filterColumn
            Column::make('status')->title('Estado')->width(100)->orderable(false)->searchable(false),
            Column::make('pdfdoc')->title('Documento')->width(120)->orderable(false)->searchable(false),
            Column::make('image')->title('Imagen')->width(120)->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('audio')->title('Audio')->width(120)->orderable(false)->searchable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->addClass('text-center')->width(180),
        ];
    }

    protected function filename(): string
    {
        return 'News_' . date('YmdHis');
    }
}
