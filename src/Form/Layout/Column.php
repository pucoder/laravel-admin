<?php

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;

/**
 * @mixin Form
 */
class Column
{
    /**
     * @var Collection
     */
    protected $fields = [];

    /**
     * @var int
     */
    protected $width;

    /**
     * @var Form|\Encore\Admin\Widgets\Form
     */
    protected $form;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    protected $widthClass = '';

    /**
     * @var null
     */
    protected $caller = null;

    /**
     * Column constructor.
     *
     * @param int $width
     * @param $form
     * @param null $callback
     */
    public function __construct($width = 12, $form, $callback = null)
    {
        if ($width < 1) {
            $this->width = intval(12 * $width);
        } elseif ($width == 1) {
            $this->width = 12;
        } else {
            $this->width = $width;
        }

        if ($this->width == 12) {
            $this->widthClass = 'col-md';
        } else {
            $this->widthClass = "col-md-{$this->width}";
        }

        $this->form = $form;
        $this->callback = $callback;

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }
    }

    /**
     * Get all filters in this column.
     *
     * @return Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[] = $field;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function width()
    {
        return $this->widthClass;
    }

    public function setWidthClass($class)
    {
        $this->widthClass .= ' ' . $class;
    }

    /**
     * set caller
     *
     * @param $caller
     * @return $this
     */
    public function setCaller($caller)
    {
        $this->caller = $caller;

        return $this;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments = [])
    {
        $arguments['caller'] = $this->caller;
        $arguments['call'] = $this;

        return $this->fields[] = call_user_func_array(
            [$this->form, 'resolveField'], [$method, $arguments]
        );
    }
}
