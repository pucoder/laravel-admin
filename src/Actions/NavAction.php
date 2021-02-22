<?php

namespace Encore\Admin\Actions;

abstract class NavAction extends Action
{
    /**
     * @var string
     */
    public $selectorPrefix = '.navbar-action-';

    /**
     * @var string
     */
    protected $icon = 'far fa-circle';

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
            return "<li class=\"nav-item\"><a href='{$href}' class='nav-link' title='{$this->name()}'><i class='{$this->icon()}'></i></a></li>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return "<li class=\"nav-item\"><a href='javascript:void(0);' class='{$this->getElementClass()} nav-link' title='{$this->name()}' {$attributes}><i class='{$this->icon()}'></i></a></li>";
    }
}
