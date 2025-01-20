<?php

namespace App\DataTables;

use App\Models\User;
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
            ->addColumn('action', function ($user) {
                return $this->renderActionColumn($user);
            })
            ->addColumn('image', function ($imguser) {
                return $this->renderImageColumn($imguser);
            })
            ->rawColumns(['image', 'action', 'editing']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function renderActionColumn($user)
    {
        $folder = 'user';
        $id = $user->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-user", $id);
        $softdelete = url("delete-user", $id);
        $view = url("view-user", $id);
        $activate = url("activate-user", $id);
        $realdelete = url("realdelete-user", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $user,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }

    protected function renderImageColumn($imguser)
    {
        $url = ("images/user/$imguser->image");

        // Verifica si el campo 'image' está vacío
        if (empty($imguser->image)) {
            return "No se ha puesto una imagen aún";
        }

        return view('layouts.media', [
            'url' => $url,
            'type' => 'image', // Indicamos que es una imagen
        ])->render();
    }

    public function query(User $model): QueryBuilder
    {
        if (Auth::check()) {
            // Obtén el usuario autenticado
            $user = Auth::user();

            // Verifica si el usuario tiene 'role_id' igual a 1
            if (auth()->user()->roles->pluck('id')[0] ?? '' === 1) {
                // Si el usuario tiene 'role_id' igual a 1, muestra todos los registros
                return $model->newQuery();
            }
            else{
                return $model->newQuery();

            }
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('image')->title('Imagen')->width(50),
            Column::make('name')->title('Nombre completo'),
            Column::make('email')->title('Correo electrónico'),
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
        return 'User_' . date('YmdHis');
    }
}
