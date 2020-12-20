<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Tree;
use Illuminate\Http\Request;

/**
 * Class TableAction.
 *
 * @method retrieveModel(Request $request)
 */
class TreeAction extends Action
{
    /**
     * @var string
     */
    public $selectorPrefix = '.tree-row-action-';

    protected $icon = 'fa-bars';

    /**
     * @var Tree
     */
    protected $parent;

    /**
     * @var array
     */
    protected $row;

    protected function icon()
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
     * Get url path of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->parent->resource();
    }

    /**
     * Get primary key value of current row.
     *
     * @return mixed
     */
    protected function getKey()
    {
        return $this->row->{$this->parent->getKeyName()};
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
     * @return array
     */
    public function parameters()
    {
        return ['_model' => $this->getModelClass()];
    }

    public function href()
    {
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
            return "<a href='{$href}' title='{$this->name()}'><i class='fa {$this->icon()}'></i></a>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return "<a data-_key='{$this->getKey()}' href='javascript:void(0);' class='{$this->getElementClass()}' title='{$this->name()}' {$attributes}><i class='fa {$this->icon()}'></i></a>";
    }
}
