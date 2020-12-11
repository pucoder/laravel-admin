<?php


namespace Encore\Admin\Controllers;


trait HasRestore
{
    public function restore($id)
    {
        $model = $this->model::withTrashed()->find($id);

        try {
            if ($model->restore()) {
                return $this->response()->success('恢复成功')->refresh()->send();
            }
            throw new \Exception('恢复失败');
        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage())->send();
        }
    }
}
