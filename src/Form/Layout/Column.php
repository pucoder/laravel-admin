<?php
/**
 * Copyright (c) 2019. Mallto.Co.Ltd.<mall-to.com> All rights reserved.
 */

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;

/**
 * Class Column
 *
 * @mixin Form
 *
 * @package Encore\Admin\Form\Layout
 */
class Column
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var string
     */
    protected $html = '';

    /**
     * @var null
     */
    protected $callRow = null;

    /**
     * Column constructor.
     *
     * @param int $width
     * @param $
     * @param $form
     * @param null $callback
     */
    public function __construct($width = 12, $form, $callback = null)
    {
        $this->width = $width;

        $this->fields = new Collection();

        $this->form = $form;
        $this->callback = $callback;

        if ($this->callback) {
            call_user_func($this->callback, $this);
        }
    }

    /**
     * Add a filter to this column.
     *
     * @param Field $field
     */
    public function add(Field $field)
    {
        $this->fields->push($field);
    }

    /**
     * @param $field
     */
    public function addField($field)
    {
        $this->fields->push($field);
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
     * Remove fields from column.
     *
     * @param $fields
     */
    public function removeFields($fields)
    {
        $this->fields = $this->fields->reject(function (Field $field) use ($fields) {
            return in_array($field->column(), $fields);
        });
    }

    /**
     * Get fields of this column.
     *
     * @return Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set column width.
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function width()
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
