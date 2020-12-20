<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\TreeAction;

class View extends TreeAction
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
