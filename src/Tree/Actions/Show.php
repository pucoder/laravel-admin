<?php

namespace Encore\Admin\Tree\Actions;

use Encore\Admin\Actions\TreeAction;

class Show extends TreeAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return trans('admin.show');
    }

    /**
     * @return string
     */
    protected function icon()
    {
        return 'fa-eye';
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }
}
