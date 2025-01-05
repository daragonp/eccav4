<?php

namespace App\DataTables;

use App\Models\News;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class LookDataTable extends DataTable
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
            ->addColumn('action', function ($news) {
                return $this->renderActionColumn($news);
            })
            ->addColumn('image', function ($imgnews) {
                return $this->renderImageColumn($imgnews);
            })
            ->rawColumns(['image', 'action', 'editing']);
    }
    protected function renderActionColumn($news)
    {
        $folder = 'news';
        $id = $news->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-news", $id);
        $softdelete = url("delete-news", $id);
        $view = url("view-news", $id);
        $activate = url("activate-news", $id);
        $realdelete = url("realdelete-news", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $news,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }

    protected function renderImageColumn($imgnews)
    {
        $url = ("images/news/$imgnews->image");

        // Verifica si el campo 'image' está vacío
        if (empty($imgnews->image)) {
            return "No se ha puesto una imagen aún";
        }

        return view('layouts.media', ['url' => $url])->render();
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\News $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(News $model): QueryBuilder
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            if (auth()->user()->roles->pluck('id')[0] ?? '' === 1) {
                // Si el usuario tiene 'role_id' igual a 1, muestra los registros seleccionados
                return $model->newQuery()->where('category',2);
            }
            else{
                return $model->newQuery()->whereNull('deleted_at');
            }
        }

        // Si el usuario no está autenticado o no tiene 'role_id' igual a 1, retorna una consulta que filtra los registros que cumplan con tus criterios específicos.
        return 'Usuario no está autenticado'; // Puedes definir tu propio criterio de filtro.


    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('news-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);

    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id')->title('ID')->width(10),
            Column::make('title')->title('Título')->width(50),
            Column::make('pdfdoc')->title('Documento')->width(10),
            Column::make('autor')->title('Autor')->width(5),
            Column::make('image')->title('Imagen')->width(5),
            Column::make('audio')->title('Audio')->width(5),
            Column::computed('action')->title('Acciones')->width(100)
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
        return 'News_' . date('YmdHis');
    }
}
