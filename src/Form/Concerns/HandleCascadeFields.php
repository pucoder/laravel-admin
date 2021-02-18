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

//        $this->row()->html($group);

        call_user_func($closure, $this);

        $hasConditions = false;

        foreach ($this->fields() as $key => $field) {
            if (isset($field->conditions) && $field->conditions) {
                $hasConditions = true;
            }

            if ($hasConditions && !$field instanceof Field\CascadeGroup && !$field instanceof Field\Display && !$field->conditions && !$field->groupClass) {
                /**@var Field $field */
                $field->setGroupClass($group);
            }
        }
//        dump($this->fields());

//        $group->end();
    }
}
