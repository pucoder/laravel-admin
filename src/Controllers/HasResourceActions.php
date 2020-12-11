<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Actions\Response;
use Encore\Admin\Form;
use Illuminate\Http\Request;

trait HasResourceActions
{
    protected $response;

    /**
     * @param string $plugin swal or toastr
     * @return Response
     */
    public function response($plugin = 'swal')
    {
        if (is_null($this->response)) {
            $this->response = new Response();
        }

        $this->response->$plugin();

        return $this->response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form(new Form(new $this->model()))->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form(new Form(new $this->model()))->store();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $model = $this->model::find($id);
        try {
            if ($model->delete()) {
                return $this->response()->success(trans('admin.delete_succeeded'))->refresh()->send();
            }
            throw new \Exception(trans('admin.delete_failed'));
        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage())->send();
        }
    }
}
