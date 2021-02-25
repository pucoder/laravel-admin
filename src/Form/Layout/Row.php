<?php

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form;

/**
 * @mixin Form
 */
class Row
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var Form|\Encore\Admin\Widgets\Form
     */
    protected $form;

    /**
     * @var string
     */
    public $html;

    /**
     * @var string
     */
    protected $widthClass = 'row';

    /**
     * @var string
     */
    protected $width = '';

    /**
     * Row constructor.
     *
     * @param Form $form
     * @param null $callback
     */
    public function __construct($form, $callback = null)
    {
        $this->form = $form;

        if ($callback) {
            call_user_func($callback, $this);
            $this->widthClass = $this->form->getRowClass();
        }
    }

    /**
     * @param string $html
     */
    public function html($html)
    {
        $this->html = $html;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function widthClass()
    {
        return $this->widthClass;
    }

    public function setDefaultClass()
    {
        $this->widthClass = 'row';

        return $this;
    }

    /**
     * @param int $width
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
     * @param string $method
     * @param array $arguments
     *
     * @return Form\Field
     */
    public function __call($method, $arguments = [])
    {
        return $this->setDefaultClass()->column(12)->setCallRow($this)->{$method}(...$arguments);
    }
}
