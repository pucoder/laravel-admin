<?php

namespace Encore\Admin\Table\Actions;

use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete extends RowAction
{
    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.delete');
    }

    /**
     * @param Model $model
     *
     * @return Response
     */
    public function handle(Model $model)
    {
        try {
            DB::transaction(function () use ($model) {
                $model->forceDelete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.delete_failed') . ": {$exception->getMessage()}");
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return $this->parent->resource() . '/' . $this->getKey() . '/delete';
    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.delete_confirm'));
    }
}
