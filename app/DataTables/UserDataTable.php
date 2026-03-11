<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UserDataTable extends DataTable
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
            ->editColumn('name', function ($row) {
                return $row->name ?? '';
            })
            ->addColumn('image', function ($row) {
                return $this->renderImageColumn($row);
            })
            ->addColumn('role', function ($row) {
                return $this->renderRoleColumn($row);
            })
            ->addColumn('status', function ($row) {
                return $row->deleted_at
                    ? '<span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-ban mr-1"></i> Inactivo</span>'
                    : '<span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>';
            })
            ->addColumn('action', function ($row) {
                return $this->renderActionColumn($row);
            })
            ->rawColumns(['image', 'role', 'status', 'action']);
    }

    protected function renderActionColumn($user)
    {
        // Generar URLs usando el helper route si las rutas están definidas
        $viewUrl = '#';
        $activateUrl = '#';
        $softdeleteUrl = '#';
        $realdeleteUrl = '#';
        $formActionUrl = '#';
        
        try {
            $viewUrl = route('users.show', $user->id);
        } catch (\Exception $e) {
            // Si la ruta no existe, usar URL directa
            $viewUrl = url("view-user/{$user->id}");
        }
        
        try {
            $activateUrl = route('users.activate', $user->id);
        } catch (\Exception $e) {
            $activateUrl = url("activate-user/{$user->id}");
        }
        
        try {
            $softdeleteUrl = route('users.delete', $user->id);
        } catch (\Exception $e) {
            $softdeleteUrl = url("delete-user/{$user->id}");
        }
        
        try {
            $realdeleteUrl = route('users.destroy', $user->id);
        } catch (\Exception $e) {
            $realdeleteUrl = url("realdelete-user/{$user->id}");
        }
        
        try {
            $formActionUrl = route('users.update', $user->id);
        } catch (\Exception $e) {
            $formActionUrl = url("update-user/{$user->id}");
        }
        
        $html = view('admin.partials.actions', [
            'id'           => $user->id,
            'view'         => $viewUrl,
            'activate'     => $activateUrl,
            'softdelete'   => $softdeleteUrl,
            'realdelete'   => $realdeleteUrl,
            'modalId'      => 'EditModal_' . $user->id,
            'formAction'   => $formActionUrl,
            'tableM'       => $user,
            'sectionType'  => 'user',
            'sectionTitle' => 'Usuario',
        ])->render();

        return $html ?? '';
    }

    protected function renderImageColumn($user)
    {
        if (empty($user->image)) {
            // Avatar por defecto si no hay imagen
            $initials = '';
            if (!empty($user->name)) {
                $nameParts = explode(' ', $user->name);
                foreach ($nameParts as $part) {
                    if (!empty($part)) {
                        $initials .= strtoupper(substr($part, 0, 1));
                        if (strlen($initials) >= 2) break;
                    }
                }
            }
            
            return '<div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-semibold">' . 
                   (empty($initials) ? '<i class="fas fa-user"></i>' : $initials) . 
                   '</div>';
        }
        
        $url = $user->avatar_url;
        return '<img src="' . $url . '" alt="' . e($user->name) . '" class="w-10 h-10 rounded-full object-cover">';
    }


    protected function renderRoleColumn($user)
    {
        try {
            // Intentar obtener el rol del usuario
            $role = $user->roles->first();
            if ($role) {
                return '<span class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700">' . 
                       e($role->name) . 
                       '</span>';
            }
        } catch (\Exception $e) {
            // Si hay un error al obtener el rol
        }
        
        // Si no se puede obtener el rol, mostrar un valor predeterminado
        return '<span class="text-slate-500 dark:text-slate-400">Sin rol</span>';
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->with('roles')->latest();
        
        // Si el usuario autenticado no es administrador, no mostrar otros administradores
        if (Auth::check()) {
            $user = Auth::user();
            
            
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
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
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
                    $("#users-table_length select, #users-table_filter input").addClass("form-control"); 
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
            Column::make('image')->title('Avatar')->width(50)->orderable(false)->searchable(false),
            Column::make('name')->title('Nombre completo')->width(200),
            Column::make('email')->title('Correo electrónico')->width(200),
            Column::make('role')->title('Rol')->width(120)->orderable(false)->searchable(false),
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
        return 'Usuarios_' . date('YmdHis');
    }
}
