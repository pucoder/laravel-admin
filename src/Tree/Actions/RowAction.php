<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Tree;
use Encore\Admin\Actions\RowAction as ActionsRowAction;

abstract class RowAction extends ActionsRowAction
{
    /**
     * @var array
     */
    protected $row;

    /**
     * @var string
     */
    public $selectorPrefix = '.tree-row-action-';

    /**
     * @var string
     */
    protected $icon = 'far fa-circle';

    /**
     * @return array|null|string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * @param Tree $tree
     * @return $this
     */
    public function setTree(Tree $tree)
    {
        $this->parent = $tree;

        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get primary key value of current row.
     *
     * @return mixed
     */
    protected function getKey()
    {
        return $this->row[$this->parent->getKeyName()];
    }

    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        $model = $this->parent->model();

        return str_replace('\\', '_', get_class($model));
    }

    /**
     * @return string
     */
    public function getElementClass()
    {
        return str_replace(' dropdown-item', '', parent::getElementClass()).' tree-item';
    }

    /**
     * Render row action.
     *
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        if ($href = $this->href()) {
            return "<a href='{$href}' class='{$this->getElementClass()}' title='{$this->name()}'><i class='{$this->icon()}'></i></a>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return "<a href='javascript:void(0);' class='{$this->getElementClass()}' title='{$this->name()}' data-_key='{$this->getKey()}' {$attributes}><i class='{$this->icon()}'></i></a>";
    }
}
