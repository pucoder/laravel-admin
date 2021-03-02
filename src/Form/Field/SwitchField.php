<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class SwitchField.
 *
 * @author songzou<zosong@126.com>
 *
 * @see https://gitbrent.github.io/bootstrap4-toggle/
 */
class SwitchField extends Field
{
    use CanCascadeFields;

    /**
     * @var string
     */
    protected $size = 'sm';

    /**
     * @var array
     */
    protected $state = [
        'on'  => ['value' => 1, 'text' => 'ON', 'style' => ''],
        'off' => ['value' => 0, 'text' => 'OFF', 'style' => 'default'],
    ];

    /**
     * @var bool
     */
    protected $plugin = true;

    /**
     * @var string
     */
    protected $changeAfter;

    /**
     * @param int    $value
     * @param string $text
     * @param string $style
     *
     * @return $this
     */
    public function on($value = 1, $text = '', $style = '')
    {
        $this->state['on'] = [
            'value' => $value,
            'text'  => $text ?: $this->state['on']['text'],
            'style' => $style ?: admin_color(),
        ];

        return $this;
    }

    /**
     * @param int    $value
     * @param string $text
     * @param string $style
     *
     * @return $this
     */
    public function off($value = 0, $text = '', $style = '')
    {
        $this->state['off'] = [
            'value' => $value,
            'text'  => $text ?: $this->state['on']['text'],
            'style' => $style ?: 'light',
        ];

        return $this;
    }

    /**
     * @param string $size lg, sm, xs
     *
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set the field options.
     *
     * @param array $states
     * @return $this
     */
    public function states($states = [])
    {
        $this->state = $states;

        return $this;
    }

    public function disablePlugin($plugin = false)
    {
        $this->plugin = $plugin;

        return $this;
    }

    public function changeAfter($script = '')
    {
        $this->changeAfter = $script;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        $this->addCascadeScript();

        $this->state['on']['style'] = $this->state['on']['style'] ?: admin_color();

        $this->addVariables([
            'state' => $this->state,
            'size'  => $this->size,
            'plugin'     => $this->plugin,
            'changeAfter'     => $this->changeAfter,
            'setUp'     => [
                'onstyle'  => $this->state['on']['style'],
                'offstyle' => $this->state['off']['style'],
                'on'       => $this->state['on']['text'],
                'off'      => $this->state['off']['text'],
                'onval'    => $this->state['on']['value'],
                'offval'   => $this->state['off']['value'],
                'size'     => $this->size,
                'width'    => 80,
                'height'    => 38,
            ],
        ]);

        return parent::render();
    }
}
