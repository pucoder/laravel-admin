<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class LogController extends AdminController
{
    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    protected function title()
    {
        return trans('admin.admin_logs');
    }

    /**
     * @return \Illuminate\Config\Repository|mixed|string
     */
    protected function model()
    {
        return config('admin.database.logs_model');
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = parent::grid();
        $grid->model()->orderByDesc('id');

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', trans('admin.user'));
        $grid->column('operate', trans('admin.operate'))->display(function ($operation) {
            return admin_route_trans($operation);
        });
        $grid->column('method', trans('admin.method'))->display(function ($method) {
            $methodColors = ['GET' => 'green', 'POST' => 'yellow', 'PUT' => 'blue', 'DELETE' => 'red'];
            $color = Arr::get($methodColors, $method, 'grey');
            return '<span class="badge bg-' . $color . '">' . $method . '</span>';
        });
        $grid->column('path', trans('admin.path'))->label('info');
        $grid->column('ip', trans('admin.ip'))->label('primary');
        $grid->column('input', trans('admin.input'))->display(function () {
            return trans('admin.view');
        })->modal(trans('admin.view') . trans('admin.input'), function ($modal) {
            $input = json_decode($modal->input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<pre>{}</pre>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
        });

        $grid->column('created_at', trans('admin.created_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $userModel = config('admin.database.users_model');
            $methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'LINK', 'UNLINK', 'COPY', 'HEAD', 'PURGE'];

            $filter->equal('user_id', trans('admin.user'))->select($userModel::pluck('name', 'id'));
            $filter->equal('method', trans('admin.method'))->select(array_combine($methods, $methods));
            $filter->like('path', trans('admin.path'));
            $filter->equal('ip', trans('admin.ip'));
        });

        return $grid;
    }
}
