<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class MenuController extends AdminController
{
    use HasResourceActions;
    use HasRestore;

    protected function model()
    {
        return config('admin.database.menus_model');
    }

    protected function title()
    {
        return trans('admin.menu');
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
            ->title(trans('admin.menu'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menus'));

                    $form->select('parent_id', trans('admin.parent_id'))->options($this->model::selectOptions(null, '顶级菜单'));
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));

                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
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
            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }

                $payload .= '&nbsp;&nbsp;&nbsp;<a href="' . $uri . '" class="dd-nodrag">' . $uri . '</a>';
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Make a form builder.
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form)
    {
        $form->display('id', 'ID');

        $form->select('parent_id', trans('admin.parent_id'))->options($this->model::selectOptions(null, '顶级菜单'));
        $form->text('title', trans('admin.title'))->rules('required');
        $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('admin.uri'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return '有关更多图标，请参见 <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
