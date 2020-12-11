<?php

namespace Encore\Admin\Http\Controllers;

trait HasResourceActions
{
    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->create()->store();
    }

    /**
     * Destroy record.
     *
     * @param $id
     * @return
     */
    public function destroy($id)
    {
        return $this->handleAction();
    }

    /**
     * Restore record.
     *
     * @param $id
     * @return
     */
    public function restore($id)
    {
        return $this->handleAction();
    }

    /**
     * Force delete record.
     *
     * @param $id
     * @return
     */
    public function delete($id)
    {
        return $this->handleAction();
    }

    /**
     * Perform action
     *
     * @return mixed
     */
    protected function handleAction()
    {
        return app(HandleController::class)->handleAction(request());
    }
}
