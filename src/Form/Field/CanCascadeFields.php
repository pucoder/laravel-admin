<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Arr;

/**
 * @property Form $form
 */
trait CanCascadeFields
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * @var null
     */
    protected $closure = null;

    /**
     * @var array
     */
    public $conditions = [];

    /**
     * @param $operator
     * @param $value
     * @param $closure
     *
     * @return $this
     */
    public function when($operator, $value, $closure = null)
    {
        if (func_num_args() == 2) {
            $closure = $value;
            $value = $operator;
            $operator = '=';
        }

        $this->formatValues($operator, $value);

        $this->addDependents($operator, $value, $closure);

        $this->applyCascadeConditions();

        return $this;
    }

    /**
     * @param string $operator
     * @param mixed  $value
     */
    protected function formatValues(string $operator, &$value)
    {
        if (in_array($operator, ['in', 'notIn'])) {
            $value = Arr::wrap($value);
        }

        if (is_array($value)) {
            $value = array_map('strval', $value);
        } else {
            $value = strval($value);
        }
    }

    /**
     * @param string   $operator
     * @param mixed    $value
     * @param \Closure $closure
     */
    protected function addDependents(string $operator, $value, \Closure $closure)
    {
        $this->conditions[] = compact('operator', 'value', 'closure');

        $dependency = [
            'column' => $this->column(),
            'index'  => count($this->conditions) - 1,
            'class'  => $this->getCascadeClass($value),
        ];

        $this->form->cascadeGroup($closure, $dependency);
    }

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        parent::fill($data);

        $this->applyCascadeConditions();
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function getCascadeClass($value)
    {
        if (is_array($value)) {
            $value = implode('-', $value);
        }

        return sprintf('cascade-%s-%s', $this->getElementClassString(), $value);
    }

    /**
     * Apply conditions to dependents fields.
     *
     * @return void
     */
    protected function applyCascadeConditions()
    {
        if ($this->form) {
            $hasConditions = false;
            $group = null;

            $this->form->fields()
                ->each(function (Form\Field $field) use (&$group, &$hasConditions) {
                    if ($field instanceof CascadeGroup) {
                        $group = $field;

                        if ($field->dependsOn($this) && $this->hitsCondition($field)) {
                            $field->visiable();
                        }
                    }

                    if ($hasConditions) {
                        /**@var Form\Field $field*/
                        if ($this->caller instanceof Form\Layout\Row) {
                            $field->setWidthClass($group);
                        } else {
                            $field->setCascadeClass($group);
                        }
                    }

                    if ($field instanceof CascadeGroup && !$field->getCall()) {
                        $hasConditions = true;
                        $field->setCall();
                    }
                });
        }
    }

    /**
     * @param CascadeGroup $group
     *
     * @throws \Exception
     * @return bool
     */
    protected function hitsCondition(CascadeGroup $group)
    {
        $condition = $this->conditions[$group->index()];

        extract($condition);

        $old = old($this->column(), $this->value());

        switch ($operator) {
            case '=':
                return $old == $value;
            case '>':
                return $old > $value;
            case '<':
                return $old < $value;
            case '>=':
                return $old >= $value;
            case '<=':
                return $old <= $value;
            case '!=':
                return $old != $value;
            case 'in':
                return in_array($old, $value);
            case 'notIn':
                return !in_array($old, $value);
            case 'has':
                return in_array($value, $old ?: []);
            case 'notHas':
                return !in_array($value, $old ?: []);
            default:
                throw new \Exception("Operator [$operator] not support.");
        }
    }

    /**
     * Add cascade scripts to contents.
     *
     * @return void
     * @throws \Throwable
     */
    protected function addCascadeScript()
    {
        if (empty($this->conditions)) {
            return;
        }

        $cascadeGroups = collect($this->conditions)->map(function ($condition) {
            return [
                'class'    => str_replace(' ', '.', $this->getCascadeClass($condition['value'])),
                'operator' => $condition['operator'],
                'value'    => $condition['value'],
            ];
        });

        Admin::view('admin::form.cascade', [
            'event'         => $this->cascadeEvent,
            'cascadeGroups' => $cascadeGroups,
            'selector'      => $this->getElementClassSelector(),
            'value'         => $this->getFormFrontValue(),
        ]);
    }

    /**
     * @return string
     */
    protected function getFormFrontValue()
    {
        switch (true) {
            case $this instanceof Checkbox:
                return <<<SCRIPT
var checked = $('{$this->getElementClassSelector()}:checked').map(function(){
  return $(this).val();
}).get();
SCRIPT;
            case $this instanceof SwitchField:
                return <<<'SCRIPT'
var checked = this.checked ? $(this).data('onval') : $(this).data('offval');
SCRIPT;
            case $this instanceof Radio:
            case $this instanceof Select:
            case $this instanceof MultipleSelect:
            case $this instanceof Text:
            case $this instanceof Textarea:
                return 'var checked = $(this).val();';
            default:
                throw new \InvalidArgumentException('Invalid form field type');
        }
    }
}
