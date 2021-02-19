<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;

trait HandleCascadeFields
{
    /**
     * @param array    $dependency
     * @param \Closure $closure
     */
    public function cascadeGroup(\Closure $closure, array $dependency)
    {
        $group = new Field\CascadeGroup($dependency, $this);

        $this->pushField($group);

        call_user_func($closure);

        $hasConditions = false;

        foreach ($this->fields() as $field) {
            if ($hasConditions) {
                /**@var Field $field*/
                $field->setCascadeClass($group);
            }

            if ($field instanceof Field\CascadeGroup && !$field->getCall()) {
                $hasConditions = true;
                $field->setCall();
            }
        }
    }
}
