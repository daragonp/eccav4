<?php

namespace App\DataTables;

use App\Models\Schedule;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class ScheduleDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('program_info', function ($row) {
                return $this->renderProgramInfoColumn($row);
            })
            ->addColumn('estado', function ($row) {
                return $row->deleted_at
                    ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>'
                    : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
            })
            ->addColumn('action', function ($schedule) {
                return $this->renderActionColumn($schedule);
            })
            ->rawColumns(['program_info', 'estado', 'action']);
    }

    protected function renderProgramInfoColumn($schedule)
    {
        // Obtener los días asociados a este emission_key
        $days = Schedule::where('emission_key', $schedule->emission_key)
            ->orderBy('day')
            ->pluck('day')
            ->toArray();

        $dayNames = [1 => 'Lu', 2 => 'Ma', 3 => 'Mi', 4 => 'Ju', 5 => 'Vi', 6 => 'Sa', 7 => 'Do'];
        $dayBadges = '';

        foreach ($days as $day) {
            if (isset($dayNames[$day])) {
                $dayBadges .= '<span class="inline-block px-1.5 py-0.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded mr-1">' . $dayNames[$day] . '</span>';
            }
        }

        $image = $schedule->image ? '<img src="' . asset('images/schedule/' . $schedule->image) . '" alt="' . $schedule->name . '" class="w-8 h-8 rounded-full object-cover mr-2">' : '<div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2"><i class="fas fa-calendar-alt text-slate-500 dark:text-slate-400 text-xs"></i></div>';

        return '<div class="flex items-center">' . $image . '<div class="min-w-0 flex-1"><div class="font-medium text-slate-900 dark:text-white text-sm truncate">' . $schedule->name . '</div><div class="text-xs text-slate-500 dark:text-slate-400 truncate">' . $schedule->host . '</div><div class="flex flex-wrap gap-0.5 mt-1">' . $dayBadges . '</div></div></div>';
    }

    protected function renderActionColumn($schedule)
    {
        // Obtenemos un registro real del bloque
        $tableM = Schedule::where('emission_key', $schedule->emission_key)->first();

        // Si no encontramos el registro, usamos el actual
        if (!$tableM) {
            $tableM = $schedule;
        }

        $daysSelected = Schedule::where('emission_key', $schedule->emission_key)->pluck('day')->toArray();

        return view('admin.partials.actions', [
            'id'         => $tableM->id,
            'view'       => url("view-schedule", $tableM->id),
            'activate'   => url("activate-schedule", $tableM->id),
            'softdelete' => url("delete-schedule", $tableM->id),
            'realdelete' => url("realdelete-schedule", $tableM->id),
            'modalId'    => 'EditModal_' . $tableM->id,
            'formAction' => url("update-schedule", $tableM->id),
            'tableM'     => $tableM,
            'sectionType' => 'schedule',
            'sectionTitle' => 'Programa',
            'daysSelected' => $daysSelected,
        ])->render();
    }

    public function query(Schedule $model): QueryBuilder
    {
        return $model->newQuery()
            ->select([
                'emission_key',
                DB::raw('MAX(id) as id'),
                DB::raw('MAX(name) as name'),
                DB::raw('MAX(slug) as slug'),
                DB::raw('MAX(start) as start'),
                DB::raw('MAX(end) as end'),
                DB::raw('MAX(host) as host'),
                DB::raw('MAX(duration) as duration'),
                DB::raw('MAX(image) as image'),
                DB::raw('MAX(about) as about'),
                DB::raw('MAX(deleted_at) as deleted_at'),
            ])
            ->groupBy('emission_key');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('schedule-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2) // Ordenar por hora de inicio
            ->parameters([
                'responsive' => true,
                'language' => [
                    'url' => asset('js/datatables-es.json')
                ],
                'processing' => true,
                'serverSide' => true,
                'autoWidth' => false,
                'pageLength' => 100, // Cambiado a 100
                'dom' => '<"dt-toolbar"<"dt-length"l><"dt-filter"f>>rtip', // Estructura modificada
                'initComplete' => 'function() {
                // Aplicar estilos a los controles de DataTables
                $("#schedule-table_length select, #schedule-table_filter input").addClass("form-control");
            }',
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('program_info')->title('Programa')->orderable(false)->searchable(true), // Habilitar búsqueda
            Column::make('name')->title('Nombre')->visible(false)->searchable(true), // Columna oculta para búsqueda
            Column::make('host')->title('Director')->visible(false)->searchable(true), // Columna oculta para búsqueda
            Column::make('start')->title('Inicio')->width(80),
            Column::make('end')->title('Fin')->width(80),
            Column::make('estado')->title('Estado')->orderable(false)->searchable(false),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->width(180),
        ];
    }

    protected function filename(): string
    {
        return 'Schedule_' . date('YmdHis');
    }
}
