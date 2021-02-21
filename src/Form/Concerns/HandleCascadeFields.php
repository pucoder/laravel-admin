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
     * @param $caller
     */
    public function cascadeGroup(\Closure $closure, array $dependency, $caller)
    {
        $group = new Field\CascadeGroup($dependency, $this);

        $this->pushField($group);

        call_user_func($closure);
    }
}
