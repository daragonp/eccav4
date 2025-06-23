<?php

namespace App\DataTables;

use App\Models\Schedule;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ScheduleDataTable extends DataTable
{
    /**
     * Construye la tabla con las columnas personalizadas.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('estado', function ($row) {
                return $row->deleted_at
                    ? '<span class="badge bg-danger">Inactivo</span>'
                    : '<span class="badge bg-success">Activo</span>';
            })
            ->addColumn('action', function ($schedule) {
                return $this->renderActionColumn($schedule);
            })
            ->filterColumn('days', function ($query, $keyword) {
                $dias = [
                    'lunes' => 1,
                    'martes' => 2,
                    'miércoles' => 3,
                    'miercoles' => 3,
                    'jueves' => 4,
                    'viernes' => 5,
                    'sábado' => 6,
                    'sabado' => 6,
                    'domingo' => 7,
                ];

                $keyword = strtolower($keyword);
                if (array_key_exists($keyword, $dias)) {
                    $query->where('day', $dias[$keyword]);
                }
            }) // ← OJO aquí estaba el problema, faltaba este cierre correcto
            ->rawColumns(['estado', 'action']);
    }

    /**
     * Renderiza la columna de acciones con modales y botones.
     */
    protected function renderActionColumn($schedule)
    {
        $folder = 'schedule';

        // Obtenemos un registro real del bloque
        $tableM = Schedule::where('emission_key', $schedule->emission_key)->firstOrFail();

        $id = $tableM->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-schedule", $id);
        $softdelete = url("delete-schedule", $id);
        $view = url("view-schedule", $id);
        $activate = url("activate-schedule", $id);
        $realdelete = url("realdelete-schedule", $id);

        $daysSelected = Schedule::where('emission_key', $schedule->emission_key)->pluck('day')->toArray();

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $tableM,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
            'daysSelected' => $daysSelected,
        ])->render();
    }

    /**
     * Consulta con GROUP BY usando emission_key para evitar repeticiones.
     */
    public function query(Schedule $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'emission_key',
                \DB::raw('MAX(id) as id'),
                \DB::raw('MAX(name) as name'),
                \DB::raw('MAX(slug) as slug'),
                \DB::raw('MAX(start) as start'),
                \DB::raw('MAX(end) as end'),
                \DB::raw('MAX(host) as host'),
                \DB::raw('MAX(duration) as duration'),
                \DB::raw('MAX(image) as image'),
                \DB::raw('MAX(about) as about'),
                \DB::raw('MAX(deleted_at) as deleted_at'),
                \DB::raw("
                    GROUP_CONCAT(
                        CASE day
                            WHEN 1 THEN 'Lunes'
                            WHEN 2 THEN 'Martes'
                            WHEN 3 THEN 'Miércoles'
                            WHEN 4 THEN 'Jueves'
                            WHEN 5 THEN 'Viernes'
                            WHEN 6 THEN 'Sábado'
                            WHEN 7 THEN 'Domingo'
                        END
                        ORDER BY day ASC SEPARATOR ', '
                    ) as days
                ")
            ])
            ->groupBy('emission_key');
    }

    /**
     * Configura el HTML del DataTable.
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
     * Columnas visibles en la tabla.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Programa'),
            Column::make('start')->title('Inicia'),
            Column::make('end')->title('Termina'),
            Column::make('host')->title('Dirige'),
            Column::make('duration')->title('Duración (min)'),
            Column::make('days')->title('Días de emisión'),
            Column::make('estado')->title('Estado'),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Nombre del archivo al exportar.
     */
    protected function filename(): string
    {
        return 'Schedule_' . date('YmdHis');
    }
}
