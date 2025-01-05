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
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($worship) {
                return $this->renderActionColumn($worship);
            })
            ->addColumn('image', function ($imgworship) {
                return $this->renderImageColumn($imgworship);
            })
            ->rawColumns(['document', 'image', 'action', 'editing']);
    }

    protected function renderActionColumn($worship)
    {
        $folder = 'worship';
        $id = $worship->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-worship", $id);
        $softdelete = url("delete-worship", $id);
        $view = url("view-worship", $id);
        $activate = url("activate-worship", $id);
        $realdelete = url("realdelete-worship", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $worship,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }

    protected function renderImageColumn($imgworship)
    {
        $url = ("images/worship/$imgworship->image");

        // Verifica si el campo 'image' está vacío
        if (empty($imgworship->image)) {
            return "No se ha puesto una imagen aún";
        }

        return view('layouts.media', ['url' => $url])->render();
    }




    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Worship $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Worship $model): QueryBuilder
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {

            if (auth()->user()->roles->pluck('id')[0] ?? '' === 1) {
                // Si el usuario tiene 'role_id' igual a 1, muestra todos los registros
                return $model->newQuery();
            } else {
                return $model->newQuery()->whereNull('deleted_at');
            }
        }

        // Si el usuario no está autenticado o no tiene 'role_id' igual a 1, retorna una consulta que filtra los registros que cumplan con tus criterios específicos.
        return $model->newQuery()->whereNull('deleted_at')->where('otro_campo', 'valor'); // Puedes definir tu propio criterio de filtro.


    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('worship-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('title')->title('Título'),
            Column::make('abstract')->title('Descripción'),
            Column::make('badge')->title('Etiqueta'),
            Column::make('image')->title('Imagen'),
            Column::make('autor')->title('Autor'),
            Column::computed('action')->title('Acciones')->width(200)
                ->exportable(true)
                ->printable(false)
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
        return 'Worship_' . date('YmdHis');
    }
}
