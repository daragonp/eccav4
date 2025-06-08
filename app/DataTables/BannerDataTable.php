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
        $folder = 'slider';
        $id = $slide->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-slider", $id);
        $softdelete = url("delete-slider", $id);
        $view = url("view-slider", $id);
        $activate = url("activate-slider", $id);
        $realdelete = url("realdelete-slider", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $slide,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }

    protected function renderImageLeft($slide)
    {
        if (empty($slide->image_left)) {
            return "No se ha puesto una imagen aún";
        }

        $url = "images/slider/$slide->image_left";

        return view('layouts.media', [
            'url' => $url,
            'type' => 'image',
        ])->render();
    }

    protected function renderImageRight($slide)
    {
        if (empty($slide->image_right)) {
            return "No se ha puesto una imagen aún";
        }

        $url = "images/slider/$slide->image_right";

        return view('layouts.media', [
            'url' => $url,
            'type' => 'image',
        ])->render();
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        try {
            return (new EloquentDataTable($query))
                ->addColumn('action', fn($slide) => $this->renderActionColumn($slide))
                ->addColumn('image_left', fn($slide) => $this->renderImageLeft($slide))
                ->addColumn('image_right', fn($slide) => $this->renderImageRight($slide))
                ->addColumn('active', function ($slide) {
                    return (int)$slide->active === 1
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-danger">Inactivo</span>';
                })
                ->rawColumns(['image_left', 'image_right', 'active', 'action']);
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

        // Si el usuario no está autenticado o no tiene 'role_id' igual a 1, retorna una consulta que filtra los registros que cumplan con tus criterios específicos.
        return $model->newQuery()->whereNull('deleted_at')->where('otro_campo', 'valor');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('carousel_images-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2);
    }


    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id')->title('ID')->width(10),
            Column::make('image_left')->title('Imagen izquierda'),
            Column::make('image_right')->title('Imagen derecha'),
            Column::make('active')->title('Estado'),
            Column::computed('action')->title('Acciones')
                ->exportable(true)
                ->printable(false)
                ->addClass('text-center'),
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
