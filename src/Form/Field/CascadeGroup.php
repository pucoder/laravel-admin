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
    protected $isHandle = false;

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
    public function isHandle()
    {
        return $this->isHandle;
    }

    public function Handle()
    {
        $this->isHandle = true;
    }

    /**
     * @return mixed|null
     */
    public function getCallForm()
    {
        return $this->callForm;
    }

    /**
     * @param $callForm
     * @return $this
     */
    public function setCallForm($callForm)
    {
        $this->callForm = $callForm;

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
