<?php

namespace App\DataTables;

use App\Models\Addon;
use App\Models\Category;
use App\Models\WeekHour;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WeekHourDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'admin.week_hours.action')
            // ->addColumn('restaurant_id', function($data) { return $data->restaurant->name; })
            // ->addColumn('day_id', function($data) { return $data->day->name; })
            ->addColumn('from', function($data) { return $data->from; })
            ->addColumn('ro', function($data) { return $data->to; });
            // ->rawColumns(['image','action']);
    }



    public function query(WeekHour $model)
    {
        return $model->newQuery();
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('week_hours-table')
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
            Column::make('id'),
            Column::make('restaurant_id'),
            Column::make('day_id'),
            Column::make('from'),
            Column::make('to'),
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
        return 'Week_hours_' . date('YmdHis');
    }
}

