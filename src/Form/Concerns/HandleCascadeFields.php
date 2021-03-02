<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;
use Encore\Admin\Form\Layout\Column;
use Encore\Admin\Form\Layout\Row;

trait HandleCascadeFields
{
    /**
     * @param \Closure $closure
     * @param array $dependency
     * @param $callForm
     * @param $callRow
     * @param $callColumn
     * @return void
     */
    public function cascadeGroup(\Closure $closure, array $dependency, $callForm, $callRow, $callColumn)
    {
        $cascadeGroup = (new Field\CascadeGroup($dependency, $this))->setCallRow($callRow);

        $this->pushField($cascadeGroup);

        if ($callForm) {
            $callForm->row()->html($cascadeGroup);
        } elseif ($callRow) {
            $callRow->column()->html($cascadeGroup);
        } elseif (!$callRow && $callColumn) {
            $callColumn->field($cascadeGroup);
        }

        call_user_func($closure, $this);

        if ($callForm) {
            $callForm->row()->html('</div>');
        } elseif ($callRow) {
            $callRow->column()->html('</div>');
        } elseif (!$callRow && $callColumn) {
            $callColumn->field('</div>');
        }
    }
}
