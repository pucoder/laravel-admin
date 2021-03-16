<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class CascadeGroup extends Field
{
    /**
     * @var array
     */
    protected $dependency;

    /**
     * @var string
     */
    protected $hide = ' hide';

    /**
     * @var null
     */
    protected $thisCallRow = null;

    /**
     * CascadeGroup constructor.
     *
     * @param array $dependency
     */
    public function __construct(array $dependency)
    {
        $this->dependency = $dependency;
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function dependsOn(Field $field)
    {
        return $this->dependency['column'] == $field->column();
    }

    /**
     * @return int
     */
    public function index()
    {
        return $this->dependency['index'];
    }

    /**
     * @return void
     */
    public function visiable()
    {
        $this->hide = '';
    }

    public function setCallRow($callRow)
    {
        $this->thisCallRow = $callRow;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $row = $this->thisCallRow ? ' col-md' : '';

        return <<<HTML
<div class="cascade-group {$this->dependency['class']}{$row}{$this->hide}">
HTML;
    }
}
