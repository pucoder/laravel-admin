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

    public function render()
    {
        return "cascade-group {$this->dependency['class']} {$this->hide}";
    }

    /**
     * @return string
     */
//    public function render()
//    {
//        return <<<HTML
//<div class="cascade-group {$this->dependency['class']} {$this->hide}">
//HTML;
//    }

    /**
     * @return void
     */
    public function end()
    {
        $this->form->row()->html('</div>');
    }
}
