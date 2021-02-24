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
    public $useSave = true;

    /**
     * @var array
     */
    protected $nestableOptions = [
        'maxDepth' => 5
    ];

    /**
     * @var bool
     */
    protected $collapseAll = false;

    /**
     * Header tools.
     *
     * @var Tools
     */
    public $tools;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $trashed = false;

    /**
     * Menu constructor.
     *
     * @param Model|null $model
     */
    public function __construct(Model $model = null, \Closure $callback = null)
    {
        $this->model = $model;

        $this->path = \request()->getPathInfo();
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
        $this->nestableOptions = array_merge($this->nestableOptions, $options);

        return $this;
    }

    /**
     * set collapse
     *
     * @param bool $collapse
     * @return $this
     */
    public function collapseAll(bool $collapse = true)
    {
        $this->collapseAll = $collapse;

        return $this;
    }

    /**
     * set maxDepth
     *
     * @param int $depth
     * @return $this
     */
    public function maxDepth(int $depth = 5)
    {
        $this->nestableOptions['maxDepth'] = $depth;

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
     * Disable save.
     *
     * @return void
     */
    public function disableSave()
    {
        $this->useSave = false;
    }

    /**
     * enable trash
     */
    public function enableTrashed()
    {
        $this->trashed = true;
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
     * Build tree grid scripts.
     *
     * @return string
     */
    protected function script()
    {
        $save_succeeded = trans('admin.save_succeeded');

        $nestableOptions = json_encode($this->nestableOptions);

        $url = url($this->path);

        $script = <<<SCRIPT
        $('#{$this->elementId}').nestable($nestableOptions);
SCRIPT;

        if ($this->collapseAll) {
            $script .= <<<SCRIPT
        $('#{$this->elementId}').nestable('collapseAll');
SCRIPT;
        }

        $script .= <<<SCRIPT
        $('.{$this->elementId}-save').click(function () {
            var serialize = $('#{$this->elementId}').nestable('serialize');

            $.post('{$url}', {
                _token: LA.token,
                _order: JSON.stringify(serialize)
            },
            function(data){
                $.pjax.reload('#pjax-container');
                toastr.success('{$save_succeeded}');
            });
        });

        $('.{$this->elementId}-tree-tools').on('click', function(e){
            var action = $(this).data('action');
            if (action === 'expand') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse') {
                $('.dd').nestable('collapseAll');
            }
        });
SCRIPT;

        return $script;
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
        return admin_url($this->path);
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
     * @return bool
     */
    protected function requestTrashed(): bool
    {
        return request()->get('_scope_') === 'trashed';
    }

    /**
     * Return all items of the tree.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->model->withQuery($this->queryCallback)->toTree($this->trashed && $this->requestTrashed());
    }

    /**
     * Variables in tree template.
     *
     * @return array
     */
    public function variables()
    {
        return [
            'id'             => $this->elementId,
            'tools'          => $this->tools->render(),
            'items'          => $this->getItems(),
            'useCreate'      => $this->useCreate,
            'useSave'        => $this->useSave,
            'trashed'        => $this->trashed,
            'requestTrashed' => $this->requestTrashed(),
            'url'            => url($this->path),
            'keyName'        => $this->model->getKeyName(),
            'branchView'     => $this->view['branch'],
            'branchCallback' => $this->branchCallback,
            'actionsCallback' => $this->actionsCallback,
            'actions'        => $this->appendActions(),
        ];
    }

    /**
     * Setup grid tools.
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
     * Render a tree.
     *
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function render()
    {
        Admin::script($this->script());

        return view($this->view['tree'], $this->variables())->render();
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->render();
    }
}
