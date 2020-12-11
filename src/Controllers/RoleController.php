<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RoleController extends AdminController
{
    use HasRestore;

    protected function model()
    {
        return config('admin.database.roles_model');
    }

    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.roles');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new $this->model());
        $grid->model()->orderByDesc('id');

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('admin.slug'));
        $grid->column('name', trans('admin.name'));

        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
                $actions->disableDelete();
            }
            $actions->disableView();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $form = new Form(new $this->model());

        $form->display('id', 'ID');

        $form->text('slug', trans('admin.slug'))->with(function ($value, $field) {
            if ($value == 'administrator') {
                $field->readonly();
            }
        })->rules('required');
        $form->text('name', trans('admin.name'))->rules('required');
        $form->checkboxGroup('permissions', trans('admin.permissions'))->options(group_permissions());

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
