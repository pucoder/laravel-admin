<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Table;

class UserController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    public function title()
    {
        return trans('admin.admin_users');
    }

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $userModel = config('admin.database.users_model');

        $table = new Table(new $userModel());
        $table->model()->orderByDesc('id');

        $table->column('id', 'ID')->sortable();
        $table->column('username', trans('admin.username'));
        $table->column('name', trans('admin.name'));
        $table->column('created_at', trans('admin.created_at'));
        $table->column('updated_at', trans('admin.updated_at'));

        $table->actions(function (Table\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDestroy();
            }
            if ($actions->row->deleted_at) {
                $actions->disableEdit();
                $actions->disableView();
                $actions->disableDestroy();
                $actions->add(new Table\Actions\Restore());
                $actions->add(new Table\Actions\Delete());
            }
        });

        $table->tools(function (Table\Tools $tools) {
            $tools->batch(function (Table\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $table->filter(function(Table\Filter $filter){
            $filter->disableIdFilter();
            $filter->scope('trashed', trans('admin.trashed'))->onlyTrashed();
            $filter->like('username', trans('admin.username'));
            $filter->like('name', trans('admin.name'));
        });

        return $table;
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
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('admin.username'));
        $show->field('name', trans('admin.name'));
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
        $userModel = config('admin.database.users_model');

        $form = new Form(new $userModel());
        $form->horizontal();

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

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
