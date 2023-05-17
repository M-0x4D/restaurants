<?php

namespace App\DataTables;

use App\Helper\Helper;
use App\Models\Offer;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OffersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            // ->addColumn('image', function($data) { return '<img src="'.$data->img_path.'" width="150" alt="" srcset="">'; })
            ->addColumn('action', 'admin.offers.action')
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CategoriesDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Offer $model)
    {
        return $model->join('offer_translations' , 'offers.id' , '=' , 'offer_translations.offer_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('offers-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('offer_id'),
            // Column::make('image'),
            Column::make('name'),
            Column::make('description'),
            Column::make('color'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Categories_' . date('YmdHis');
    }
}
