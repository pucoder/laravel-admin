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
    protected $hide = ' d-none';

    /**
     * @var null
     */
    protected $thisCallRow = null;

    /**
     * CascadeGroup constructor.
     *
     * @param array $dependency
     * @param $form
     */
    public function __construct(array $dependency, $form)
    {
        $this->dependency = $dependency;
        $this->form = $form;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $row = $this->thisCallRow ? ' col-md' : '';

        return <<<HTML
<div class="cascade-group {$this->dependency['class']}{$this->hide}{$row}">
HTML;
    }
}
