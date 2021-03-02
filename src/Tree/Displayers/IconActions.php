<?php

namespace Encore\Admin\Tree\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Tree\Actions\ColumnEdit;
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
     * @var array
     */
    protected $columnEdit = [];

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

        if ($action->getCalledClass() === str_replace('\\', '_', ColumnEdit::class)) {
            $action->setColumn($this->columnEdit['column'], $this->columnEdit['label']);

            $action->setDefault($this->row[$this->columnEdit['column']]);
        }
    }

    /**
     * @param string $column
     * @param string $label
     * @return $this
     * @throws \Exception
     */
    public function useColumnEdit(string $column, string $label = '')
    {
        if (in_array(Edit::class, $this->defaultClass)) {
            array_delete($this->defaultClass, Edit::class);
        }

        if (!in_array(ColumnEdit::class, $this->defaultClass)) {
            $this->columnEdit = [
                'column' => $column,
                'label' => $label
            ];

            array_unshift($this->defaultClass, ColumnEdit::class);
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
            if ($this->columnEdit) {
                array_delete($this->defaultClass, ColumnEdit::class);
            } else {
                array_delete($this->defaultClass, Edit::class);
            }
        } elseif ($this->columnEdit && !in_array(ColumnEdit::class, $this->defaultClass)) {
            array_push($this->defaultClass, ColumnEdit::class);
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
     * @throws \Throwable
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

        return Admin::view($this->view, $variables);
    }
}
