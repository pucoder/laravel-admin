<?php

namespace Encore\Admin\Tree\Displayers;

use Encore\Admin\Tree;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractDisplayer
{
    /**
     * @var Tree
     */
    protected $tree;

    /**
     * Create a new displayer instance.
     *
     * @param Tree     $tree
     */
    public function __construct(Tree $tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return Tree
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * Get url path of current resource.
     *
     * @return string
*/
    public function getResource()
    {
        return $this->tree->resource();
    }

    /**
     * Display method.
     *
     * @return mixed
     */
    abstract public function display();
}
