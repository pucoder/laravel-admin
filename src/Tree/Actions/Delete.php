<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\TreeAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete extends TreeAction
{
    /**
     * @var string
     */
    protected $method = 'DELETE';

    public function name()
    {
        return trans('admin.delete');
    }

    /**
     * @return array|null|string
     */
    public function icon()
    {
        return 'fas fa-trash';
    }

    /**
     * @return string
     */
    public function getElementClass()
    {
        return parent::getElementClass().' text-danger';
    }

    public function handle(Model $model)
    {
        try {
            DB::transaction(function () use ($model) {
                $model->forceDelete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.delete_failed')." : {$exception->getMessage()}");
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return "{$this->getResource()}/{$this->getKey()}/delete";
    }

    public function dialog()
    {
        $this->confirm(trans('admin.delete_confirm'));
    }
}
