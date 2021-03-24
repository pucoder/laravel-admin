<?php

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form;

/**
 * Class Row
 *
 * @mixin Form
 *
 * @package Encore\Admin\Form
 */
class Row
{
    /**
     * Callback for add field to current row.s.
     *
     * @var \Closure
     */
    protected $callback;

    /**
     * Parent form.
     *
     * @var Form
     */
    protected $form;

    /**
     * Fields in this row.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Default field width for appended field.
     *
     * @var int
     */
    protected $width = 12;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var bool
     */
    protected $push = false;

    /**
     * @var string
     */
    protected $html = '';

    /**
     * Row constructor.
     *
     * Row constructor.
     * @param $form
     * @param \Closure|null $callback
     */
    public function __construct($form, \Closure $callback = null)
    {
        $this->callback = $callback;

        $this->form = $form;

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }
    }

    public function html($string)
    {
        $this->html = $string;

        return $this;
    }

    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param int $width integer from 1 to 12
     * @param null $callback
     * @return Column
     */
    public function column($width = 12, $callback = null)
    {
        if (func_num_args() == 1 && $width instanceof \Closure) {
            $callback = $width;
            $width = 12;
        }

        return $this->columns[] = new Column($width, $this->form, $callback);
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param bool $push
     */
    public function push($push = true)
    {
        $this->push = $push;
    }

    /**
     * @return bool
     */
    public function isPush(): bool
    {
        return $this->push;
    }

    /**
     * Set width for a incomming field.
     *
     * @param int $width
     *
     * @return string
     */
    public function width($width = 12)
    {
        if ($this->form->isHorizontal()) {
            return 'horizontal';
        } else {
            return 'row';
        }
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if ($this->form->isCreating() && $method === 'display') {
            return null;
        }

        return $this->column()->setCallRow($this)->{$method}(...$arguments);
    }
}
