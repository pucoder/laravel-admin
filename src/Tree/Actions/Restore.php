<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Restore extends RowAction
{
    /**
     * @var string
     */
    protected $method = 'PUT';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.restore');
    }

    protected function icon()
    {
        return 'fa-undo';
    }

    /**
     * @return string
     */
    public function getHandleRoute()
    {
        return "{$this->getResource()}/{$this->getKey()}/restore";
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
//                $model->restore();
//            });
//        } catch (\Exception $exception) {
//            return $this->response()->error(trans('admin.restore_failed') . ": {$exception->getMessage()}");
//        }
//
//        return $this->response()->success(trans('admin.restore_succeeded'))->refresh();
//    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.restore_confirm'), '', ['confirmButtonColor' => '#d33']);
    }
}
