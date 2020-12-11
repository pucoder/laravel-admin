<?php

namespace Encore\Admin\Form\Actions;

use Illuminate\Contracts\Support\Renderable;

class _List implements Renderable
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function render()
    {
        $text = trans('admin.list');

        return <<<HTML
<div class="btn-group float-right mr-2">
    <a href="{$this->path}" class="btn btn-sm btn-default" title="$text">
        <i class="fa fa-list"></i><span> {$text} </span>
    </a>
</div>
HTML;
    }
}
