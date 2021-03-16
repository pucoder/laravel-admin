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
     * @var Collection
     */
    protected $tabs;

    /**
     * @var int
     */
    protected $offset = 0;

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
     * @param \Closure $content
     * @param bool     $active
     *
     * @return $this
     */
    public function append($title, \Closure $callback, $active = false)
    {
        $rows = $this->collectFields($callback);

        $id = 'form-'.($this->tabs->count() + 1);

        $this->tabs->push(compact('id', 'title', 'rows', 'active'));

        return $this;
    }

    /**
     * Collect fields under current tab.
     *
     * @param \Closure $callback
     * @return array
     */
    protected function collectFields(\Closure $callback): array
    {
        call_user_func($callback, $this->form);

        $rows = [];

        foreach ($this->form->getRows() as $row) {
            if ($row->isPush()) {
                continue;
            }
            $row->push();

            array_push($rows, $row);
        }

        return $rows;
    }

    /**
     * Get all tabs.
     *
     * @return Collection
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
    public function isEmpty()
    {
        return $this->tabs->isEmpty();
    }
}
