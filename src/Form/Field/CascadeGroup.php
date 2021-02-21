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
    protected $hide = 'd-none';

    /**
     * @var bool
     */
    protected $isCall = false;

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

    public function setCall()
    {
        $this->isCall = true;
    }

    /**
     * @return bool
     */
    public function getCall()
    {
        return $this->isCall;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        return "cascade-group {$this->dependency['class']} {$this->hide}";
    }
}
