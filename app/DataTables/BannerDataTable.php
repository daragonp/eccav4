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
        $leftType = $slide->left_media_type;
        $rightType = $slide->right_media_type;

        if ($slide->left_media_src) {
            if ($leftType === 'video') {
                $leftContent = '<div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-900 mr-2"><video muted playsinline class="w-full h-full object-cover"><source src="' . $slide->left_media_src . '" type="' . ($slide->left_media_mime ?? 'video/mp4') . '"></video></div>';
            } elseif ($leftType === 'youtube') {
                $leftContent = '<div class="w-16 h-16 rounded-lg bg-black text-white flex items-center justify-center mr-2"><i class="fab fa-youtube text-lg"></i></div>';
            } else {
                $leftContent = '<img src="' . $slide->left_media_src . '" alt="Imagen izquierda" class="w-16 h-16 rounded-lg object-cover mr-2">';
            }
        } else {
            $leftContent = '<div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2"><i class="fas fa-image text-slate-500 dark:text-slate-400"></i></div>';
        }

        if ($slide->right_media_src) {
            if ($rightType === 'video') {
                $rightContent = '<div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-900 mr-2"><video muted playsinline class="w-full h-full object-cover"><source src="' . $slide->right_media_src . '" type="' . ($slide->right_media_mime ?? 'video/mp4') . '"></video></div>';
            } elseif ($rightType === 'youtube') {
                $rightContent = '<div class="w-16 h-16 rounded-lg bg-black text-white flex items-center justify-center mr-2"><i class="fab fa-youtube text-lg"></i></div>';
            } else {
                $rightContent = '<img src="' . $slide->right_media_src . '" alt="Imagen derecha" class="w-16 h-16 rounded-lg object-cover mr-2">';
            }
        } else {
            $rightContent = '<div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2"><i class="fas fa-image text-slate-500 dark:text-slate-400"></i></div>';
        }

        return '<div class="flex items-center">' .
            '<div class="flex mr-2">' . $leftContent . $rightContent . '</div>' .
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
                if ($row->active) {
                    return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Activo
                    </span>';
                } else {
                    return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-200 dark:border-rose-700 shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Inactivo
                    </span>';
                }
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
            return $model->newQuery()->where('active', true);
        }
    }

    // Si el usuario no está autenticado o no tiene 'role_id' igual a 1
    return $model->newQuery()->where('active', true);
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
