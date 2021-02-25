<?php

namespace Encore\Admin\Form;

use Encore\Admin\AbstractForm;
use Encore\Admin\Form;

/**
 * @mixin Form
 */
class TabForm extends AbstractForm
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var bool
     */
    public $active = false;

    /**
     * @var Form
     */
    protected $form;

    /**
     * TabItem constructor.
     * @param string $title
     * @param Form $form
     * @param \Closure $callback
     * @param bool $active
     */
    public function  __construct($title, $form, $callback, $active = false)
    {
        $this->title = $title;
        $this->form = $form;
        $this->active = $active;

        $this->id = uniqid('from-tab-');

        if ($callback) {
            $callback($this);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return $this->form->fields();
    }

    /**
     * @param $method
     * @param array $arguments
     * @return Field\Nullable|mixed
     */
    public function resolveField($method, $arguments = [])
    {
        return $this->form->resolveField($method, $arguments);
    }
}

