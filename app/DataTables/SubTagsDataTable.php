<?php

namespace App\DataTables;

use App\Helper\Helper;
use App\Models\Tag;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SubTagsDataTable extends DataTable
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
            ->addColumn('action', 'admin.subtags.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SubTagsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Tag $model)
    {
        return $model->join('tag_translations' , 'tags.id' , '=' , 'tag_translations.tag_id')->where('language_id' , Helper::currentLanguage(App::getLocale())->id)->whereParentId($this->tag->id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('tags-table')
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
            Column::make('tag_id'),
            Column::make('name'),
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
        return 'SubTags_' . date('YmdHis');
    }
}
