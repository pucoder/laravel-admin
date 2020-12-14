<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Tree;

abstract class NavAction extends Action
{
    /**
     * @var string
     */
    public $selectorPrefix = '.navbar-action-';

    /**
     * @var string
     */
    protected $icon = 'fa-bars';

    /**
     * @var Tree
     */
    protected $parent;

    /**
     * @var array
     */
    protected $row;

    /**
     * @return string
     */
    protected function icon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    protected function getModelClass()
    {
        return '';
    }

    /**
     *
     */
    public function href()
    {
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        if ($href = $this->href()) {
            return "<li><a href='{$href}' title='{$this->name()}'><i class='fa {$this->icon()}'></i></a></li>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return "<li><a href='javascript:void(0);' class='{$this->getElementClass()}' title='{$this->name()}' {$attributes}><i class='fa {$this->icon()}'></i></a></li>";
    }
}
