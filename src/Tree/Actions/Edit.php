<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\TreeAction;

class Edit extends TreeAction
{
    /**
     * @return array|null|string
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
        return 'fa-edit';
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
