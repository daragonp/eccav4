<?php

namespace App\DataTables;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class PodcastDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('title', function ($row) {
                return $row->title ?? '';
            })
            ->addColumn('image', function ($row) {
                return $this->renderImageColumn($row);
            })
            ->addColumn('audio', function ($row) {
                return $this->renderAudioColumn($row);
            })
            ->addColumn('status', function ($row) {
                return $row->deleted_at
                    ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-ban mr-1"></i> Inactivo</span>'
                    : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
            })
            ->addColumn('action', function ($row) {
                return $this->renderActionColumn($row);
            })
            ->rawColumns(['image', 'audio', 'status', 'action']);
    }

    protected function renderActionColumn($podcast)
    {
        // Generar URLs usando el helper route si las rutas están definidas
        $viewUrl = '#';
        $activateUrl = '#';
        $softdeleteUrl = '#';
        $realdeleteUrl = '#';
        $formActionUrl = '#';
        
        try {
            $viewUrl = route('podcasts.show', $podcast->id);
        } catch (\Exception $e) {
            // Si la ruta no existe, usar URL directa
            $viewUrl = url("view-podcast/{$podcast->id}");
        }
        
        try {
            $activateUrl = route('podcasts.activate', $podcast->id);
        } catch (\Exception $e) {
            $activateUrl = url("activate-podcast/{$podcast->id}");
        }
        
        try {
            $softdeleteUrl = route('podcasts.delete', $podcast->id);
        } catch (\Exception $e) {
            $softdeleteUrl = url("delete-podcast/{$podcast->id}");
        }
        
        try {
            $realdeleteUrl = route('podcasts.destroy', $podcast->id);
        } catch (\Exception $e) {
            $realdeleteUrl = url("realdelete-podcast/{$podcast->id}");
        }
        
        try {
            $formActionUrl = route('podcasts.update', $podcast->id);
        } catch (\Exception $e) {
            $formActionUrl = url("update-podcast/{$podcast->id}");
        }
        
        $html = view('admin.partials.actions', [
            'id'           => $podcast->id,
            'view'         => $viewUrl,
            'activate'     => $activateUrl,
            'softdelete'   => $softdeleteUrl,
            'realdelete'   => $realdeleteUrl,
            'modalId'      => 'EditModal_' . $podcast->id,
            'formAction'   => $formActionUrl,
            'tableM'       => $podcast,
            'sectionType'  => 'podcast',
            'sectionTitle' => 'Podcast',
        ])->render();

        return $html ?? '';
    }

    protected function renderImageColumn($podcast)
    {
        if (empty($podcast->image)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-image-slash"></i> Sin imagen</span>';
        }
        
        $url = asset("images/podcast/{$podcast->image}");
        return '<img src="' . $url . '" alt="' . e($podcast->title) . '" class="w-16 h-16 object-cover rounded">';
    }

    protected function renderAudioColumn($podcast)
    {
        if (empty($podcast->audio_file)) {
            return '<span class="text-slate-500 dark:text-slate-400"><i class="fas fa-volume-mute"></i> Sin audio</span>';
        }
        
        $url = asset("audio/podcast/{$podcast->audio_file}");
        return '<audio controls class="w-full max-w-xs"><source src="' . e($url) . '" type="audio/mpeg">Tu navegador no soporta este elemento de audio.</audio>';
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Podcast $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Podcast $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('podcast-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
                'initComplete' => 'function() { 
                    $("#podcast-table_length select, #podcast-table_filter input").addClass("form-control"); 
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(50),
            Column::make('title')->title('Título')->width(200),
            Column::make('author')->title('Autor')->width(150),
            Column::make('published_date')->title('Fecha de publicación')->width(120),
            Column::make('image')->title('Imagen')->width(80)->orderable(false)->searchable(false),
            Column::make('audio')->title('Audio')->width(150)->orderable(false)->searchable(false),
            Column::make('status')->title('Estado')->width(100)->orderable(false)->searchable(false),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(180)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Podcasts_' . date('YmdHis');
    }
}