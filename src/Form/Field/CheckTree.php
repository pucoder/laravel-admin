<?php


namespace Encore\Admin\Form\Field;


class CheckTree extends Checkbox
{
    /**
     * @var array
     */
    protected $relatedField = [];

    /**
     * @param string $related
     * @param string $field
     * @return $this
     */
    public function related(string $related, string $field): CheckTree
    {
        $this->relatedField = [$related, $field];

        return $this;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function render()
    {
        $this->addVariables([
            'column'       => $this->column,
            'checked'       => $this->checked,
            'checkAllClass' => uniqid('check-all-'),
            'options'       => $this->getOptions(),
            'relatedField'  => json_encode($this->relatedField),
        ]);

        return parent::fieldRender();
    }
}
