<?php


namespace Encore\Admin\Form\Field;


use Encore\Admin\Form\Field;

class JsTree extends Field
{
    /**
     * @var bool
     */
    protected $opened = false;

    /**
     * @param bool $opened
     * @return $this
     */
    public function opened($opened = true)
    {
        $this->opened = $opened;

        return $this;
    }

    protected function getOptions()
    {
        $value = explode(',', $this->value);
        $return = [];
        foreach ($this->options as $option) {
            $opened = false;
            if (in_array((string)$option['id'], $value)) {
                $opened = true;
                $option['state']['selected'] = true;
            }

            $option['state']['opened'] = $opened ?: $this->opened;

            array_push($return, $option);
        }

        return $return;
    }

    public function render()
    {
        $this->addVariables([
            'value' => $this->value,
            'options' => $this->getOptions(),
        ]);

        return parent::fieldRender();
    }
}
