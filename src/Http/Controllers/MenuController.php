<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Tree;

class MenuController extends AdminController
{
    public function title()
    {
        return trans('admin.auth_menus');
    }

    public function setModel()
    {
        return config('admin.database.menus_model');
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->title(trans('admin.new'));
                    $form->action(admin_url('auth_menus'));

                    $form->select('group', trans('admin.group'))
                        ->options(key_eq_value(config('admin.menu_group', [])));
                    $form->select('parent_id', trans('admin.parent_id'))->options($this->model::selectOptions())->default(0)->rules('required');
                    $form->text('title', trans('admin.title'))->rules('required')->prepend(new Form\Field\Icon('icon'));
                    $form->text('uri', trans('admin.uri'));
                    $form->hidden('_saved')->default(1);

                    $column->append($form);
                });
            });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $tree = new Tree(new $this->model());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $title = admin_trans($branch['title']);
            $payload = "<i class='{$branch['icon']}'></i>&nbsp;<strong>{$title}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag d-none d-md-inline\">$uri</a>";
            }

            return $payload;
        });

        $tree->enableTrashed();

        $tree->actions(function (Tree\Displayers\Actions $actions) {
//            $actions->useColumnEdit('title', '标题');
            if ($actions->trashed && $actions->requestTrashed) {
                $actions->disableEdit();
                $actions->disableView();
                $actions->disableDestroy();
            }

            if ($actions->row['deleted_at']) {
                $actions->add(new Tree\Actions\Restore());
                $actions->add(new Tree\Actions\Delete());
            }
        });

        return $tree;
    }

    protected function detail($id)
    {
        $show = new Show($this->model::findOrFail($id));

        $show->field('id', 'ID');

        $show->field('title', trans('admin.title'));
        $show->field('uri', trans('admin.uri'));

        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('admin.menus'))
            ->description(trans('admin.edit'))
            ->row($this->form()->edit($id));
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

        $form->select('group', trans('admin.group'))
            ->options(key_eq_value(config('admin.menu_group', [])));
        $form->select('parent_id', trans('admin.parent_id'))->options($this->model::selectOptions())->default(0)->rules('required');
        $form->text('title', trans('admin.title'))->rules('required')->prepend(new Form\Field\Icon('icon'));
        $form->text('uri', trans('admin.uri'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
