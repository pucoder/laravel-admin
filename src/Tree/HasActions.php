<?php

namespace Encore\Admin\Tree;

use Closure;
use Encore\Admin\Tree\Displayers\IconActions;

trait HasActions
{
    /**
     * Callback for table actions.
     *
     * @var []Closure
     */
    protected $actionsCallback = [];

    /**
     * Actions column display class.
     *
     * @var string
     */
    protected $actionsClass;

    /**
     * Set table action callback.
     *
     * @param Closure|string $actions
     *
     * @return $this
     */
    public function actions($actions)
    {
        if ($actions instanceof Closure) {
            $this->actionsCallback[] = $actions;
        }

        return $this;
    }

    /**
     * Get action display class.
     *
     * @return \Illuminate\Config\Repository|mixed|string
     */
    public function getActionClass()
    {
        if ($this->actionsClass) {
            return $this->actionsClass;
        }

        return IconActions::class;
    }

    /**
     * @param string $actionClass
     *
     * @return $this
     */
    public function setActionClass(string $actionClass)
    {
        $this->actionsClass = $actionClass;

        return $this;
    }

    protected function displayUsing($abstract)
    {
        /** @var IconActions $displayer */
        $displayer = new $abstract($this);

        return $displayer;
    }

//    /**
//     * Disable all actions.
//     *
//     * @return $this
//     */
//    public function disableActions(bool $disable = true)
//    {
//        return $this->option('show_actions', !$disable);
//    }

    protected function appendActions()
    {
//        if (!$this->option('show_actions')) {
//            return;
//        }

        return $this->displayUsing($this->getActionClass());
    }
}
