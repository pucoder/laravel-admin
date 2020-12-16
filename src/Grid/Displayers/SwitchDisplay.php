<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class SwitchDisplay extends AbstractDisplayer
{
    protected $scriptAfter = '';

    /**
     * @var array
     */
    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    protected function overrideStates($states)
    {
        if (empty($states)) {
            return;
        }

        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }
    }

    public function display($states = [], $scriptAfter = '')
    {
        $this->overrideStates($states);

        $this->scriptAfter = $scriptAfter;

        return Admin::component('admin::grid.inline-edit.switch', [
            'class'         => 'grid-switch-'.str_replace('.', '-', $this->getName()),
            'key'           => $this->getKey(),
            'resource'      => $this->getResource(),
            'name'          => $this->getPayloadName(),
            'states'        => $this->states,
            'checked'       => $this->states['on']['value'] == $this->getValue() ? 'checked' : '',
            'scriptAfter'   => $this->scriptAfter,
        ]);
    }
}
