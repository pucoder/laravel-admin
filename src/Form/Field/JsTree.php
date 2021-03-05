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
     * @var array
     */
    protected $relatedField = [];

    /**
     * @var int
     */
    protected $rootValue = 0;

    /**
     * @param string $related
     * @param string $field
     * @return $this
     */
    public function related($related, $field)
    {
        $this->relatedField = [$related, $field];

        return $this;
    }

    /**
     * @param int $rootValue
     * @return $this
     */
    public function setRootValue($rootValue = 0)
    {
        $this->rootValue = $rootValue;

        return $this;
    }

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
        $return = [];

        foreach ($this->options as $option) {
            $opened = false;
            if (in_array((string)$option['id'], explode(',', $this->value))) {
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
            'rootValue' => $this->rootValue,
            'value' => $this->value,
            'options' => $this->getOptions(),
            'relatedField'  => json_encode($this->relatedField),
        ]);

        return parent::fieldRender();
    }
}
