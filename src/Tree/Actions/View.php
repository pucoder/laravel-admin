<?php

namespace Encore\Admin\Tree\Actions;

class View extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.show');
    }

    /**
     * @return array|null|string
     */
    public function icon()
    {
        return 'fas fa-eye';
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }
}
