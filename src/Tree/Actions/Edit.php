<?php

namespace Encore\Admin\Tree\Actions;

class Edit extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.edit');
    }

    /**
     * @return array|null|string
     */
    public function icon()
    {
        return 'fas fa-edit';
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
