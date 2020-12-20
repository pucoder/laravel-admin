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
