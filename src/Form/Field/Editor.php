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
var editor = $('#{$this->getUniqueId()}');
if (editor.length > 0) {
    CKEDITOR.replace('{$this->getUniqueId()}', JSON.parse('{$config}'));
    editor.addClass('active');
} else {
    editor = $('.editor:not(.active)');
    var id = editor.attr('id');
    CKEDITOR.replace(id + '', JSON.parse('{$config}'));
    editor.addClass('active');
}
SCRIPT;

        return parent::render();
    }
}
