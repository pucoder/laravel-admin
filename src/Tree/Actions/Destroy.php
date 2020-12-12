<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\Response;
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
    protected function icon()
    {
        return 'fa-trash';
    }

    /**
     * @return string
     */
    public function getHandleRoute()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }

    /**
     * @param Model $model
     *
     * @return Response
     */
//    public function handle(Model $model)
//    {
//        try {
//            DB::transaction(function () use ($model) {
//                $model->delete();
//            });
//        } catch (\Exception $exception) {
//            return $this->response()->error(trans('admin.destroy_failed') . ": {$exception->getMessage()}");
//        }
//
//        return $this->response()->success(trans('admin.destroy_succeeded'))->refresh();
//    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.destroy_confirm'), '', ['confirmButtonColor' => '#d33']);
    }
}
