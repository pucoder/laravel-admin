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
        $group = new Field\CascadeGroup($dependency);

        $this->pushField($group);

        call_user_func($closure, $this);

        $group->end();
    }
}
