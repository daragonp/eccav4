<?php

namespace App\DataTables;

use App\Models\Banner;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class BannerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    protected function renderActionColumn($slide)
    {
        return view('admin.partials.actions', [
            'id'         => $slide->id,
            'view'       => url("view-slider", $slide->id),
            'activate'   => url("activate-slider", $slide->id),
            'softdelete' => url("delete-slider", $slide->id),
            'realdelete' => url("realdelete-slider", $slide->id),
            'modalId'    => 'EditModal_'.$slide->id,
            'formAction' => url("update-slider", $slide->id),
            'tableM'     => $slide,
            'sectionType'=> 'slider',
            'sectionTitle' => 'Carrusel',
        ])->render();
    }

    protected function renderBannerInfo($slide)
    {
        // Renderizar imágenes del carrusel
        $leftImage = $slide->image_left 
            ? '<img src="' . asset('images/slider/' . $slide->image_left) . '" alt="Imagen izquierda" class="w-16 h-16 rounded-lg object-cover mr-2">' 
            : '<div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2"><i class="fas fa-image text-slate-500 dark:text-slate-400"></i></div>';
            
        $rightImage = $slide->image_right 
            ? '<img src="' . asset('images/slider/' . $slide->image_right) . '" alt="Imagen derecha" class="w-16 h-16 rounded-lg object-cover mr-2">' 
            : '<div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2"><i class="fas fa-image text-slate-500 dark:text-slate-400"></i></div>';
        
        return '<div class="flex items-center">' . 
            '<div class="flex mr-2">' . $leftImage . $rightImage . '</div>' . 
            '<div class="min-w-0 flex-1">' . 
            '<div class="font-medium text-slate-900 dark:text-white text-sm truncate">Carrusel #' . $slide->id . '</div>' . 
            '<div class="text-xs text-slate-500 dark:text-slate-400 truncate">ID: ' . $slide->id . '</div>' . 
            '</div></div>';
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        try {
            return (new EloquentDataTable($query))
                ->addColumn('banner_info', function ($row) {
                    return $this->renderBannerInfo($row);
                })
                ->addColumn('estado', function ($row) {
                    return $row->deleted_at 
                        ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>'
                        : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
                })
                ->addColumn('action', fn($slide) => $this->renderActionColumn($slide))
                ->rawColumns(['banner_info', 'estado', 'action']);
        } catch (\Throwable $e) {
            logger()->error('Error en BannerDataTable: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Banner $model): QueryBuilder
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            if (Auth::user()->roles->pluck('id')[0] ?? '' === 1) {
                return $model->newQuery();
            } else {
                return $model->newQuery()->whereNull('deleted_at');
            }
        }

        // Si el usuario no está autenticado o no tiene 'role_id' igual a 1, retorna una consulta que filtre los registros que cumplan con tus criterios específicos.
        return $model->newQuery()->whereNull('deleted_at')->where('otro_campo', 'valor');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('slider-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'language' => [
                    'url' => asset('js/datatables-es.json')
                ],
                'processing' => true,
                'serverSide' => true,
                'autoWidth' => false,
                'pageLength' => 100,
                'dom' => '<"dt-toolbar"<"dt-length"l><"dt-filter"f>>rtip',
                'initComplete' => 'function() {
                    // Aplicar estilos a los controles de DataTables
                    $("#slider-table_length select, #slider-table_filter input").addClass("form-control");
                }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('banner_info')->title('Carrusel')->orderable(false)->searchable(true),
            Column::make('id')->title('ID')->visible(false)->searchable(true),
            Column::make('estado')->title('Estado')->orderable(false)->searchable(false),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->width(180),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Banner_' . date('YmdHis');
    }
}