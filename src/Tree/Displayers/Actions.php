<?php

namespace Encore\Admin\Tree\Displayers;

abstract class Actions extends AbstractDisplayer
{
    protected $disableAll = false;

    abstract public function display();
}
