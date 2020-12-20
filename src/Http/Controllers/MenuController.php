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
        return trans('admin.admin_menus');
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
                    $form->horizontal();
                    $form->title(trans('admin.new'));
                    $form->action(admin_url('admin_menus'));

                    $menuModel = config('admin.database.menus_model');

                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
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
        $menuModel = config('admin.database.menus_model');

        $tree = new Tree(new $menuModel());


        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='{$branch->icon}'></i>&nbsp;<strong>{$branch->title}</strong>";

            if ($branch->children->isEmpty()) {
                if (url()->isValidUrl($branch->uri)) {
                    $uri = $branch->uri;
                } else {
                    $uri = admin_url($branch->uri);
                }

                $payload .= '<a href="'.$uri.'" class="dd-nodrag pl-2">'.$uri.'</a>';
            }

            return $payload;
        });

        $tree->enableTrashed();
        $tree->actions(function (Tree\Displayers\Actions $actions) use ($tree) {
            if ($tree->useTrashed && request()->get('_scope_') === 'trashed') {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableDestroy();
            }
            if ($actions->row->deleted_at) {
                $actions->add(new Tree\Actions\Restore());
                $actions->add(new Tree\Actions\Delete());
            }
        });

        return $tree;
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
        $menuModel = config('admin.database.menus_model');

        $show = new Show($menuModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('title', trans('admin.title'));
        $show->field('uri', trans('admin.uri'));
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
        $menuModel = config('admin.database.menus_model');

        $form = new Form(new $menuModel());
        $form->horizontal();

        $form->display('id', 'ID');

        $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
        $form->text('title', trans('admin.title'))->rules('required')->prepend(new Form\Field\Icon('icon'));
        $form->text('uri', trans('admin.uri'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
