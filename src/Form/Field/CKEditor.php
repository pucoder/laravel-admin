<?php

namespace Encore\Admin\Form\Field;

class CKEditor extends Textarea
{
    public function render()
    {
        $this->addVariables([
            'config' => array_merge(config('admin.extensions.ckeditor.config', []), $this->options)
        ]);

        return parent::render();
    }
}
