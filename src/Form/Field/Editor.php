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
        $config = json_encode(array_merge(config('admin.extensions.editor.config', []), $this->options));

        $this->script = <<<SCRIPT
CKEDITOR.replace('{$this->getUniqueId()}', JSON.parse('{$config}'));
SCRIPT;

        return parent::render();
    }
}
