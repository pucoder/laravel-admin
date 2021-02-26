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
     * @return void
     */
    public function cascadeGroup(\Closure $closure, array $dependency, $callForm)
    {
        $cascadeGroup = (new Field\CascadeGroup($dependency, $this))->setCallForm($callForm);

        $this->pushField($cascadeGroup);

        if ($cascadeGroup->getCallForm()) {
            $cascadeGroup->getCallForm()->setRowClass($cascadeGroup);
        }

        call_user_func($closure, $this);

        if ($cascadeGroup->getCallForm()) {
            $cascadeGroup->getCallForm()->setDefaultClass();
        }

        $hasConditions = false;
        $thisCascadeGroup = null;

//        dump($this->fields());
        foreach ($this->fields() as $field) {
            /**
             * @var Field\CascadeGroup $thisCascadeGroup
             * @var Field $field
             */
            if ($hasConditions && !$field instanceof Field\CascadeGroup) {
                if ($field->getCall()) {
                    $field->setCascadeClass($thisCascadeGroup);
                }
                elseif (!$thisCascadeGroup->getCallForm() && $field->getCallRow() instanceof Row) {
                    $field->getCallColumn()->setWidthClass($thisCascadeGroup);
                }
                elseif (!$thisCascadeGroup->getCallForm() && $field->getCallColumn() instanceof Column) {
                    $field->setCascadeClass($thisCascadeGroup);
                }
            }

            if ($field instanceof Field\CascadeGroup && !$field->isHandle()) {
                $hasConditions = true;

                $thisCascadeGroup = $field;

                $thisCascadeGroup->handle();
            }
        }

    }
}
