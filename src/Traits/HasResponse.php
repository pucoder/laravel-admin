<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Actions\Response;

trait HasResponse
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @param bool $toastr 弹窗模式，默认toastr，false = swal
     * @return Response
     */
    public function response($toastr = true)
    {
        if (is_null($this->response)) {
            $this->response = new Response();
        }

        if ($toastr) {
            $this->response->toastr();
        } else {
            $this->response->swal();
        }

        return $this->response;
    }
}
