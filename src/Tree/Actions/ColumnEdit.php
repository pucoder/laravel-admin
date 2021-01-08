<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\TreeAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnEdit extends TreeAction
{
    /**
     * @var string
     */
    protected $column = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $default = '';

    /**
     * @param string $column
     * @param string $label
     * @return $this
     */
    public function setColumn(string $column, string $label = '')
    {
        $this->column = $column;

        $this->label = $label ?: ucfirst($column);

        return $this;
    }

    /**
     * @param string $default
     * @return $this
     */
    public function setDefault(string $default = '')
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function name()
    {
        return trans('admin.edit');
    }

    /**
     * @return string
     */
    protected function icon()
    {
        return 'fa-edit';
    }

    /**
     * @return string
     */
//    public function getHandleRoute()
//    {
//        return "{$this->getResource()}/{$this->getKey()}";
//    }

    /**
     *
     * @param Model $model
     * @param Request $request
     * @return Response
     */
    public function handle(Model $model, Request $request)
    {

        try {
            $column = $request->get('column');
            $data = $request->only($column);

            DB::transaction(function () use ($model, $data) {
                $model->fill($data)->save();
            });
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.update_failed') . ": {$exception->getMessage()}");
        }

        return $this->response()->success(trans('admin.update_succeeded'))->refresh();
    }

    /**
     * edit form
     */
    public function form()
    {
        $this->hidden('column')->value($this->column);
        $this->text($this->column, $this->label)->default($this->default)->required();
    }
}
