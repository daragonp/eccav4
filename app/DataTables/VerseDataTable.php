<?php

namespace App\DataTables;

use App\Models\Verse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class VerseDataTable extends DataTable
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
            ->addColumn('action', function ($quote) {
                return $this->renderActionColumn($quote);
            })
            ->addColumn('image', function ($imgquote) {
                return $this->renderImageColumn($imgquote);
            })
            ->addColumn('video', function ($videoQuote) {
                return $this->renderVideoColumn($videoQuote);
            })
            ->addColumn('audio', function ($audioQuote) {
                return $this->renderAudioColumn($audioQuote);
            })
            ->rawColumns(['image', 'video', 'audio', 'action']);
    }


    protected function renderActionColumn($quote)
    {
        $folder = 'quote';
        $id = $quote->id;
        $modalId = 'EditModal_' . $id;
        $formAction = url("update-quote", $id);
        $softdelete = url("delete-quote", $id);
        $view = url("view-quote", $id);
        $activate = url("activate-quote", $id);
        $realdelete = url("realdelete-quote", $id);

        return view('layouts.actions', [
            'folder' => $folder,
            'tableM' => $quote,
            'modalId' => $modalId,
            'formAction' => $formAction,
            'softdelete' => $softdelete,
            'view' => $view,
            'activate' => $activate,
            'realdelete' => $realdelete,
        ])->render();
    }

    protected function renderImageColumn($imgquote)
    {
        $url = "images/bible/$imgquote->image";

        if (empty($imgquote->image)) {
            return "No se ha puesto una imagen aún";
        }

        return view('layouts.media', [
            'url' => $url,
            'type' => 'image', // Indicamos que es una imagen
        ])->render();
    }


    protected function renderVideoColumn($videoQuote)
{
    $url = asset("documents/quote/{$videoQuote->video}");

    if (empty($videoQuote->video)) {
        return "No se ha cargado un documento aún";
    }

    return '<a href="' . $url . '" target="_blank">Ver</a>';
}

    protected function renderAudioColumn($audioQuote)
    {
        $url = "audio/quote/$audioQuote->audio";

        if (empty($audioQuote->audio)) {
            return "No se ha cargado un audio aún";
        }

        return view('layouts.media', [
            'url' => $url,
            'type' => 'audio', // Indicamos que es un audio
        ])->render();
    }



    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Verse $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Verse $model): QueryBuilder
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            if (auth()->user()->roles->pluck('id')[0] ?? '' === 1) {
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
            ->setTableId('verse-table')
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
            // Column::make('id')->title('ID')->width(10),
            Column::make('date')->title('Fecha'),
            Column::make('image')->title('Imagen'),
            Column::make('video')->title('Documento'),
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
        return 'Verse_' . date('YmdHis');
    }
}
