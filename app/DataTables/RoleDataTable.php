<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class RoleDataTable extends DataTable
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
            ->addColumn('action', function ($role) {
                return $this->renderActionColumn($role);
            })
            ->addColumn('users_count', function ($role) {
                // Contar usuarios asignados a este rol
                try {
                    return $role->users()->count() ?? 0;
                } catch (\Exception $e) {
                    // Si la relación no existe, mostrar 0
                    return 0;
                }
            })
            ->addColumn('status', function ($role) {
                // Mostrar estado basado en si el rol está activo o no
                // Asumiendo que hay un campo 'active' o similar en el modelo Role
                $isActive = false;
                
                // Verificar si el campo active existe
                if (isset($role->active)) {
                    $isActive = (bool)$role->active;
                } 
                // Si no existe el campo active, verificar si tiene deleted_at
                elseif (isset($role->deleted_at)) {
                    $isActive = is_null($role->deleted_at);
                }
                // Si no existe ninguno de los campos, asumir que está activo
                else {
                    $isActive = true;
                }
                
                return $isActive
                    ? '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>'
                    : '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-ban mr-1"></i> Inactivo</span>';
            })
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model): QueryBuilder
    {
        $query = $model->newQuery()->latest();
        
        // Intentar cargar la relación con usuarios si existe
        try {
            $query->withCount('users');
        } catch (\Exception $e) {
            // Si la relación no existe, continuar sin ella
        }
        
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
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
                    $("#roles-table_length select, #roles-table_filter input").addClass("form-control"); 
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
            Column::make('name')->title('Nombre del rol')->width(200),
            Column::make('users_count')->title('Usuarios asignados')->width(120)->orderable(true)->searchable(false),
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
        return 'Roles_' . date('YmdHis');
    }

    /**
     * Render the action column.
     *
     * @param Role $role
     * @return string
     */
    protected function renderActionColumn($role)
    {
        // Generar URLs usando el helper route si las rutas están definidas
        $viewUrl = '#';
        $activateUrl = '#';
        $softdeleteUrl = '#';
        $realdeleteUrl = '#';
        $formActionUrl = '#';
        
        try {
            $viewUrl = route('roles.show', $role->id);
        } catch (\Exception $e) {
            // Si la ruta no existe, usar URL directa
            $viewUrl = url("view-role/{$role->id}");
        }
        
        try {
            $activateUrl = route('roles.activate', $role->id);
        } catch (\Exception $e) {
            $activateUrl = url("activate-role/{$role->id}");
        }
        
        try {
            $softdeleteUrl = route('roles.delete', $role->id);
        } catch (\Exception $e) {
            $softdeleteUrl = url("delete-role/{$role->id}");
        }
        
        try {
            $realdeleteUrl = route('roles.destroy', $role->id);
        } catch (\Exception $e) {
            $realdeleteUrl = url("realdelete-role/{$role->id}");
        }
        
        try {
            $formActionUrl = route('roles.update', $role->id);
        } catch (\Exception $e) {
            $formActionUrl = url("update-role/{$role->id}");
        }
        
        $html = view('admin.partials.actions', [
            'id'           => $role->id,
            'view'         => $viewUrl,
            'activate'     => $activateUrl,
            'softdelete'   => $softdeleteUrl,
            'realdelete'   => $realdeleteUrl,
            'modalId'      => 'EditModal_' . $role->id,
            'formAction'   => $formActionUrl,
            'tableM'       => $role,
            'sectionType'  => 'role',
            'sectionTitle' => 'Rol',
        ])->render();

        return $html ?? '';
    }
}