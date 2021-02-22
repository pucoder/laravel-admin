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
     * Remove the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
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
        return $this->form()->restore($id);
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
        return $this->form()->delete($id);
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
