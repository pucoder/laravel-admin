<?php

namespace Encore\Admin\Controllers;

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
        return $this->form()->store();
    }

    /**
     * Remove the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        return $this->form()->destroy($id);
        return $this->handleAction();
    }

    /**
     * restore the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->handleAction();
    }

    /**
     * delete the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
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
