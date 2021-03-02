<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\Response;
use Encore\Admin\Actions\TreeAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnEdit extends TreeAction
{
    /**
     * @var string
     */
    protected $column = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $default = '';

    /**
     * @param string $column
     * @param string $label
     * @return $this
     */
    public function setColumn(string $column, string $label = '')
    {
        $this->column = $column;

        $this->label = $label ?: ucfirst($column);

        return $this;
    }

    /**
     * @param string $default
     * @return $this
     */
    public function setDefault(string $default = '')
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function name()
    {
        return trans('admin.edit');
    }

    /**
     * @return string
     */
    protected function icon()
    {
        return 'fas fa-edit';
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }

    /**
     * edit form
     */
    public function form()
    {
        $this->hidden('_method')->value('PUT');

        $this->text($this->column, $this->label)->default($this->default)->required();
    }

    /**
     * Render row action.
     *
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        $this->addScript();

        return "<a data-_key='{$this->getKey()}' url='{$this->getResource()}/{$this->getKey()}' href='javascript:void(0);' class='{$this->getElementClass()}' title='{$this->name()}' {$this->formatAttributes()}><i class='{$this->icon()}'></i></a>";
    }
}
