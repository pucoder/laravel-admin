<?php

namespace Encore\Admin\Tree\Displayers;

use Encore\Admin\Actions\TreeAction;
use Encore\Admin\Admin;
use Encore\Admin\Tree;
use Encore\Admin\Tree\Actions\Destroy;
use Encore\Admin\Tree\Actions\View;
use Encore\Admin\Tree\Actions\Edit;

class IconActions extends Actions
{
    protected $view = 'admin::tree.actions.icon';

    /**
     * @var array
     */
    protected $custom = [];

    /**
     * @var array
     */
    protected $default = [];

    /**
     * @var array
     */
    protected $defaultClass = [
        Edit::class,
        View::class,
        Destroy::class
    ];

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    public $row = [];

    /**
     * @param $row
     * @return $this
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * @var Tree
     */
    protected $tree;

    public function add(TreeAction $action)
    {
        array_push($this->custom, $action);

        $this->prepareAction($action);

        return $this;
    }

    /**
     * @var TreeAction $action
     */
    protected function prependDefaultActions()
    {
        $this->default = [];

        foreach ($this->defaultClass as $class) {
            $action = new $class();

            $this->prepareAction($action);

            array_push($this->default, $action);
        }
    }

    /**
     * @param TreeAction $action
     */
    protected function prepareAction(TreeAction $action)
    {
        $action->setTree($this->tree)->setRow($this->row);;
    }

    /**
     * Disable view action.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, View::class);
        } elseif (!in_array(View::class, $this->defaultClass)) {
            array_push($this->defaultClass, View::class);
        }

        return $this;
    }

    /**
     * Disable destroy.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableDestroy(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Destroy::class);
        } elseif (!in_array(Destroy::class, $this->defaultClass)) {
            array_push($this->defaultClass, Destroy::class);
        }

        return $this;
    }

    /**
     * Disable edit.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Edit::class);
        } elseif (!in_array(Edit::class, $this->defaultClass)) {
            array_push($this->defaultClass, Edit::class);
        }

        return $this;
    }

    /**
     * @param array $callback
     * @return mixed|string|void
     * @throws \Throwable
     */
    public function display($callback = [])
    {
        if (is_array($callback) && !empty($callback)) {
            foreach ($callback as $item) {
                $item->call($this, $this);
            }
        }

        if ($this->disableAll) {
            return '';
        }

        $this->prependDefaultActions();

        $variables = [
            'default'  => $this->default,
            'custom'   => $this->custom
        ];

        $this->custom = [];

        if (empty($variables['default']) && empty($variables['custom'])) {
            return;
        }

        return Admin::view($this->view, $variables);
    }
}
