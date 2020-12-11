<?php

namespace Encore\Admin\Grid\Actions;

use Encore\Admin\Actions\RowAction;

class Delete extends RowAction
{
    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.delete');
    }

    /**
     * @return string
     */
    public function getHandleRoute()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question(trans('admin.delete_confirm'), '', ['confirmButtonColor' => '#d33']);
    }
}
