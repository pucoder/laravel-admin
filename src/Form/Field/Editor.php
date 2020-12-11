<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    protected static $js = [
        '/vendor/laravel-admin/ckeditor/ckeditor.js',
    ];

    public function render()
    {
        $name = $this->elementName ?: $this->formatName($this->column);

        $config = json_encode(array_merge(config('admin.extensions.editor.config'), $this->options));

        $this->script = <<<EOT
CKEDITOR.replace('{$name}', $config);
EOT;
        return parent::render();
    }
}
