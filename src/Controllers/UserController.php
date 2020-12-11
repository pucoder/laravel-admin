<?php

namespace Encore\Admin\Controllers;

use App\Admin\Actions\Restore;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class UserController extends AdminController
{
    use HasRestore;

    protected function model()
    {
        return config('admin.database.users_model');
    }

    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.administrator');
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
        $grid->column('username', trans('admin.username'));
        $grid->column('name', trans('admin.name'));
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() === 1) {
                $actions->disableDelete();
            }
            if ($actions->row->deleted_at) {
                $actions->disableEdit();
                $actions->disableDelete();
                $actions->add(new Restore());
            }

            $actions->disableView();
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
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $this->model());

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'))->uniqueName()->removable();
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function (Form $form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->config('maximumSelectionLength', '1')->options($roleModel::pluck('name', 'id'))->optionDataAttributes('permissions', $roleModel::pluck('permissions', 'id'));
        $form->checkboxGroup('permissions', trans('admin.permissions'))->options(group_permissions())->related('roles', 'permissions');

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        return $form;
    }
}
