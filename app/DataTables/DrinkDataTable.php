<?php

namespace App\DataTables;

use App\Helper\Helper;
use App\Models\Addon;
use App\Models\Category;
use App\Models\Drink;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DrinkDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('image', function($data) { return '<img src="'.$data->img_path.'" width="100" alt="" srcset="">'; })
            ->addColumn('action', 'admin.drinks.action')
            ->addColumn('price', function($data) { return $data->price; })
            ->rawColumns(['image','action']);
    }



    public function query(Drink $model)
    {
        return $model->join('drink_translations' , 'drinks.id' , '=' , 'drink_translations.drink_id')
        ->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->newQuery();
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('drinks-table')
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
            Column::make('drink_id'),
            Column::make('name'),
            Column::make('image'),
            Column::make('price'),
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
        return 'Drinks_' . date('YmdHis');
    }
}

