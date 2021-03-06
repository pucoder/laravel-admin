<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\TreeAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete extends TreeAction
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
        return "{$this->getResource()}/{$this->getKey()}/delete";
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
//                $model->forceDelete();
//            });
//        } catch (\Exception $exception) {
//            return $this->response()->error(trans('admin.delete_failed') . ": {$exception->getMessage()}");
//        }
//
//        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
//    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.delete_confirm'), '', ['confirmButtonColor' => '#d33']);
    }
}
