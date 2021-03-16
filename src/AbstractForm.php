<?php


namespace Encore\Admin;


use Encore\Admin\Form\Field;
use Encore\Admin\Form\Layout\Row;

abstract class AbstractForm
{

    /**
     * Field rows in form.
     *
     * @var array
     */
    protected $rows = [];

    /**
     * @var bool
     */
    protected $horizontal = false;

    /**
     * @var bool
     */
    protected $container = true;

    /**
     * Add a row in form.
     *
     * @param Closure $callback
     *
     * @return Row
     */
    public function row(\Closure $callback = null): Row
    {
        return $this->rows[] = new Row($this, $callback);
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @return void
     */
    public function horizontal()
    {
        $this->horizontal = true;
    }

    /**
     * @return bool
     */
    public function isHorizontal(): bool
    {
        return $this->horizontal;
    }

    /**
     * @return void
     */
    public function disableContainer()
    {
        $this->container = false;
    }

    /**
     * @return bool
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field
     */
    public function __call($method, $arguments)
    {
        $arguments['callForm'] = $this;

        $field = $this->resolveField($method, $arguments);

        if (!$field instanceof Field) {
            return $field;
        }

        $this->row()->column()->add($field);

        return $field;
    }

    public abstract function fields();

    public abstract function resolveField($method, $arguments = []);
}
