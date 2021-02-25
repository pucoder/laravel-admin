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
     * @var null
     */
    protected $callForm = null;

    /**
     * CascadeGroup constructor.
     *
     * @param array $dependency
     * @param $form
     * @param null $call
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

    /**
     * @return bool
     */
    public function isCall()
    {
        return $this->isCall;
    }

    public function onCall()
    {
        $this->isCall = true;
    }

    /**
     * @return mixed|null
     */
    public function getCall()
    {
        return $this->callForm;
    }

    /**
     * @param $call
     * @return $this
     */
    public function setCall($call)
    {
        $this->callForm = $call;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        return "cascade-group {$this->dependency['class']} {$this->hide}";
    }
}
