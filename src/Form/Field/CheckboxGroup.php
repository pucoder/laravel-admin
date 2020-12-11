<?php

namespace Encore\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;

class CheckboxGroup extends MultipleSelect
{
    protected $inline = true;

    protected $canCheckAll = false;

    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    /**
     * @var string
     */
    protected $cascadeEvent = 'ifChanged';

    /**
     * @var array
     */
    protected $relatedField = [];

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $options;
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * Add a checkbox above this component, so you can select all checkboxes by click on it.
     *
     * @return $this
     */
    public function canCheckAll()
    {
        $this->canCheckAll = true;

        return $this;
    }

    /**
     * Set checked.
     *
     * @param array|callable|string $checked
     *
     * @return $this
     */
    public function checked($checked = [])
    {
        if ($checked instanceof Arrayable) {
            $checked = $checked->toArray();
        }

        $this->checked = (array) $checked;

        return $this;
    }

    /**
     * Draw inline checkboxes.
     *
     * @return $this
     */
    public function inline()
    {
        $this->inline = true;

        return $this;
    }

    /**
     * Draw stacked checkboxes.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->inline = false;

        return $this;
    }

    /**
     * @param string $related
     * @param string $field
     * @return $this
     */
    public function related(string $related, string $field)
    {
        $this->relatedField = [$related, $field];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $relatedField = json_encode($this->relatedField, true);
        $checkAllClass = uniqid('check-all-');
        $this->addVariables([
            'checked'     => $this->checked,
            'inline'      => $this->inline,
            'canCheckAll' => $this->canCheckAll,
            'checkAllClass' => $checkAllClass,
        ]);

        $this->script .= <<<SCRIPT
var related_field = JSON.parse('{$relatedField}');

if (related_field.length > 0) {
    var related = $('.form-control.' + related_field[0]);

    checkRelated(related);

    related.change(function () {
        checkRelated($(this));
    });

    function checkRelated(related) {
        var data_fileds = [];
        $.each(related.find('option:selected'), function (key, val) {
            data_fileds.push($(val).data(related_field[1]));
        });

        $('{$this->getElementClassSelector()}:disabled').iCheck('uncheck').prop('disabled', false).parent().attr({'aria-disabled' : false}).removeClass('disabled').parent().removeClass('disabled');
        $.each($('{$this->getElementClassSelector()}'), function (k, v) {
            if ($.inArray($(v).val(), data_fileds.flat()) !== -1) {
                $(v).iCheck('check').prop('disabled', true).parent().attr({'aria-disabled' : true}).addClass('disabled').parent().addClass('disabled');
            }
            checkGroup(v);
        });
    }
}

$.each($('#{$this->column} .row {$this->getElementClassSelector()}'), function() {
    checkGroup($(this));
});

$('{$this->getElementClassSelector()}').iCheck({
    checkboxClass:'icheckbox_minimal-blue'
}).on('ifChanged', function () {
    checkGroup($(this));
});

$('.{$checkAllClass}').iCheck({
    checkboxClass:'icheckbox_minimal-blue'
}).on('ifChanged', function () {
    $(this).parents('.row:first').find('{$this->getElementClassSelector()}:not(:disabled)').iCheck(this.checked ? 'check' : 'uncheck');
});

function checkGroup(field) {
    var group_fields = $(field).parents('.row:first');
    var fields = group_fields.find('{$this->getElementClassSelector()}').length;
    var checked_fields = group_fields.find('{$this->getElementClassSelector()}:checked').length;
    if (checked_fields === fields) {
        group_fields.find('.{$checkAllClass}').prop('checked', true).parent().attr({'aria-checked' : true}).addClass('checked');
    } else {
        group_fields.find('.{$checkAllClass}').prop('checked', false).parent().attr({'aria-checked' : false}).removeClass('checked');
    }

    if ($(field).prop('disabled') && checked_fields === fields) {
        group_fields.find('.{$checkAllClass}').prop('disabled', true).parent().attr({'aria-disabled' : true}).addClass('disabled').parent().addClass('disabled');
    } else {
        group_fields.find('.{$checkAllClass}').prop('disabled', false).parent().attr({'aria-disabled' : false}).removeClass('disabled').parent().removeClass('disabled');
    }
}
SCRIPT;

        return parent::render();
    }
}
