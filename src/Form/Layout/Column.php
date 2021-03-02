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
     * @var Form|\Encore\Admin\Widgets\Form
     */
    protected $form;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var int
     */
    protected $width = 12;

    /**
     * @var null
     */
    protected $callRow = null;

    /**
     * @var string
     */
    public $html = '';

    /**
     * Column constructor.
     *
     * Column constructor.
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
        }

        $this->form = $form;
        $this->callback = $callback;

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }
    }

    public function html($html)
    {
        $this->html = $html;
    }

    public function field($html)
    {
        $this->fields[] = $html;
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
    public function widthClass()
    {
        if ($this->width === 12) {
            $widthClass = "col-md";
        } else {
            $widthClass = "col-md-{$this->width}";
        }

        return $widthClass;
    }

    /**
     * set callRow
     *
     * @param $callRow
     * @return $this
     */
    public function setCallRow($callRow)
    {
        $this->callRow = $callRow;

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
        $arguments['callRow'] = $this->callRow;
        $arguments['callColumn'] = $this;

        return $this->fields[] = call_user_func_array(
            [$this->form, 'resolveField'], [$method, $arguments]
        );
    }
}
