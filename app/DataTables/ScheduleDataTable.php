<?php

namespace App\DataTables;

use App\Models\Schedule;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ScheduleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($schedule) {
                return $this->renderActionColumn($schedule);
            })
            ->rawColumns(['image', 'action', 'editing']);
    }
    protected function renderActionColumn($schedule)
    {
        $folder = 'schedule';
        $id = $schedule->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-schedule", $id);
        $softdelete = url("delete-schedule", $id);
        $view = url("view-schedule", $id);
        $activate = url("activate-schedule", $id);
        $realdelete = url("realdelete-schedule", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $schedule,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Schedule $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Schedule $model): QueryBuilder
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
            ->setTableId('schedule-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(7);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {

        return [
            Column::make('name')->title('Programa'),
            Column::make('start')->title('Inicia'),
            Column::make('end')->title('Termina'),
            Column::make('host')->title('Dirige'),
            Column::make('duration')->title('Duración'),
            Column::make('day')->title('Día'),
            Column::make('deleted_at')->title('Estado'),
            Column::computed('action')->title('Acciones')
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
        return 'Schedule_' . date('YmdHis');
    }
}
