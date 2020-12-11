<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RoleController extends AdminController
{
    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    protected function title()
    {
        return trans('admin.roles');
    }

    /**
     * @return \Illuminate\Config\Repository|mixed|string
     */
    protected function model()
    {
        return config('admin.database.roles_model');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = parent::grid();
        $grid->model()->orderByDesc('id');

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('admin.slug'));
        $grid->column('name', trans('admin.name'));
        $grid->column('permissions', trans('admin.permissions'))->width(500)->display(function ($permissions) {
            $names = [];
            foreach (set_permissions() as $key => $value) {
                if ($permissions && in_array($value, $permissions)) {
                    array_push($names, $key);
                }
            }
            return $names;
        })->label();

        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
                $actions->disableDestroy();
            }
            if ($actions->row->deleted_at) {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableDestroy();
                $actions->add(new Grid\Actions\Restore());
                $actions->add(new Grid\Actions\Delete());
            }
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function(Grid\Filter $filter){
            $filter->disableIdFilter();
            $filter->scope('trashed', trans('admin.trashed'))->onlyTrashed();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = parent::detail($id);

        $show->field('id', 'ID');
        $show->field('slug', trans('admin.slug'));
        $show->field('name', trans('admin.name'));
        $show->field('permissions', trans('admin.permissions'))->as(function ($permissions) {
            $names = [];
            foreach (set_permissions() as $key => $value) {
                if ($permissions && in_array($value, $permissions)) {
                    array_push($names, $key);
                }
            }
            return $names;
        })->label();
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $form = parent::form();

        $form->display('id', 'ID');

        $form->text('slug', trans('admin.slug'))->rules('required');
        $form->text('name', trans('admin.name'))->rules('required');
        $form->checkboxGroup('permissions', trans('admin.permissions'))->options(group_permissions());

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
