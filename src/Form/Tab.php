<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class Tab
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * @var []TabItem
     */
    protected $tabs = [];

    /**
     * Tab constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->tabs = new Collection();
    }

    /**
     * Append a tab section.
     *
     * @param string   $title
     * @param \Closure $callback
     * @param bool     $active
     *
     * @return $this
     */
    public function add($title, \Closure $callback, $active = false)
    {
        $id = 'form-'.($this->tabs->count() + 1);

        $rows = $this->collectFields($callback);

        $this->tabs->push(compact('id', 'title', 'rows', 'active'));

        return $this;
    }

    protected function collectFields(\Closure $callback)
    {
        call_user_func($callback, $this->form);

        $rows = [];

        foreach ($this->form->getRows() as $row) {
            if ($row->getShow()) {
                continue;
            }

            $row->setShow();

            array_push($rows, $row);
        }

        return $rows;
    }

    /**
     * Get all tabs.
     *
     * @return array
     */
    public function getTabs()
    {
        // If there is no active tab, then active the first.
        if ($this->tabs->filter(function ($tab) {
            return $tab['active'];
        })->isEmpty()) {
            $first = $this->tabs->first();
            $first['active'] = true;

            $this->tabs->offsetSet(0, $first);
        }

        return $this->tabs;
    }

    /**
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->tabs->isEmpty();
    }
}
