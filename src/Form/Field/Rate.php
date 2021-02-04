<?php

namespace Encore\Admin\Form\Field;

class Rate extends Text
{
    public function render()
    {
        $this->prepend('')
            ->append(admin_color('<span class="input-group-text bg-%s"><i class="far fa-fw">%</i></span>'))
            ->defaultAttribute('style', 'text-align:right;')
            ->defaultAttribute('placeholder', 0);

        return parent::render();
    }
}
