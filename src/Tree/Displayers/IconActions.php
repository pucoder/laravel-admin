<?php

namespace Encore\Admin\Tree\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Tree\Actions\Destroy;
use Encore\Admin\Tree\Actions\Edit;
use Encore\Admin\Actions\TreeAction;
use Encore\Admin\Tree\Actions\Show;

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
        Show::class,
        Destroy::class
    ];

    /**
     * @param TreeAction $action
     *
     * @return $this
     */
    public function add(TreeAction $action)
    {
        $this->prepareAction($action);

        array_push($this->custom, $action);

        return $this;
    }

    /**
     * Prepend default `edit` `view` `delete` actions.
     */
    protected function prependDefaultActions()
    {
        foreach ($this->defaultClass as $class) {
            /** @var TreeAction $action */
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
        $action->setTree($this->tree)->setRow($this->row);
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
     * Disable view action.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Show::class);
        } elseif (!in_array(Show::class, $this->defaultClass)) {
            array_push($this->defaultClass, Show::class);
        }

        return $this;
    }

    /**
     * Disable destroy.
     *
     * @param bool $disable
     *
     * @return $this.
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
     * @param null $callback
     * @return mixed|string|void
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        if ($this->disableAll) {
            return '';
        }

        $this->prependDefaultActions();

        $variables = [
            'default' => $this->default,
            'custom'  => $this->custom,
        ];

        $this->default = [];
        $this->custom = [];

        if (empty($variables['default']) && empty($variables['custom'])) {
            return;
        }

        return Admin::component($this->view, $variables);
    }
}
