<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Tree\HasActions;
use Encore\Admin\Tree\Tools;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class Tree implements Renderable
{
    use HasActions;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var string
     */
    protected $elementId = 'tree-';

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var \Closure
     */
    protected $queryCallback;

    /**
     * View of tree to render.
     *
     * @var string
     */
    protected $view = [
        'tree'   => 'admin::tree',
        'branch' => 'admin::tree.branch',
    ];

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var null
     */
    protected $branchCallback = null;

    /**
     * @var bool
     */
    public $useCreate = true;

    /**
     * @var bool
     */
    public $useTrashed = false;

    /**
     * @var bool
     */
    public $useSave = true;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Header tools.
     *
     * @var Tools
     */
    public $tools;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var
     */
    protected $row;

    /**
     * Menu constructor.
     *
     * @param Model|null $model
     * @param Closure|null $callback
     */
    public function __construct(Model $model = null, Closure $callback = null)
    {
        $this->model = $model;

        $this->path = request()->getPathInfo();

        $this->elementId .= uniqid();

        $this->setupTools();

        if ($callback instanceof \Closure) {
            call_user_func($callback, $this);
        }

        $this->initBranchCallback();
    }

    /**
     * Setup tree tools.
     */
    public function setupTools()
    {
        $this->tools = new Tools($this);
    }

    /**
     * Initialize branch callback.
     *
     * @return void
     */
    protected function initBranchCallback()
    {
        if (is_null($this->branchCallback)) {
            $this->branchCallback = function ($branch) {
                $key = $branch[$this->model->getKeyName()];
                $title = $branch[$this->model->getTitleColumn()];

                return "$key - $title";
            };
        }
    }

    /**
     * Set branch callback.
     *
     * @param \Closure $branchCallback
     *
     * @return $this
     */
    public function branch(\Closure $branchCallback)
    {
        $this->branchCallback = $branchCallback;

        return $this;
    }

    /**
     * Set query callback this tree.
     *
     * @param Closure $callback
     * @return Tree
     */
    public function query(\Closure $callback)
    {
        $this->queryCallback = $callback;

        return $this;
    }

    /**
     * Set nestable options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function nestable($options = [])
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Disable create.
     *
     * @return void
     */
    public function disableCreate()
    {
        $this->useCreate = false;
    }

    /**
     * Enable trashed.
     *
     * @return Tree
     */
    public function enableTrashed()
    {
        $this->useTrashed = true;
    }

    /**
     * Disable save.
     *
     * @return void
     */
    public function disableSave()
    {
        $this->useSave = false;
    }

    /**
     * Save tree order from a input.
     *
     * @param string $serialize
     *
     * @return bool
     */
    public function saveOrder($serialize)
    {
        $tree = json_decode($serialize, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        $this->model->saveOrder($tree);

        return true;
    }

    /**
     * Set view of tree.
     *
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return $this->path;
    }

    /**
     * @return Model|null
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->model->getKeyName();
    }

    /**
     * Return all items of the tree.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->model->withQuery($this->queryCallback)->getTree(($this->useTrashed && request()->get('_scope_') === 'trashed'));
    }

    /**
     * Setup table tools.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function tools(Closure $callback)
    {
        call_user_func($callback, $this->tools);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        return Admin::view($this->view['tree'], [
            'id'         => $this->elementId,
            'tools'      => $this->tools->render(),
            'items'      => $this->getItems(),
            'useCreate'  => $this->useCreate,
            'useTrashed'  => $this->useTrashed,
            'useSave'    => $this->useSave,
            'url'        => url($this->path),
            'options'    => $this->options,
            'keyName'        => $this->getKeyName(),
            'branchView'     => $this->view['branch'],
            'branchCallback' => $this->branchCallback,
            'actionsCallback' => $this->actionsCallback,
            'actions'        => $this->appendActions(),
        ]);
    }

    /**
     * Get the string contents of the table view.
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->render();
    }
}
