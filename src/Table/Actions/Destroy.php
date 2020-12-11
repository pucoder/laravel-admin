<?php

namespace Encore\Admin\Table\Actions;

use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Destroy extends RowAction
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
        return trans('admin.destroy');
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return $this->parent->resource() . '/' . $this->getKey();
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
                $model->delete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.destroy_failed') . ": {$exception->getMessage()}");
        }

        return $this->response()->success(trans('admin.destroy_succeeded'))->refresh();
    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.destroy_confirm'));
    }
}
