<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Traits\HasResponse;
use Illuminate\Http\Request;

class Permission
{
    use HasResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (config('admin.check_permissions') === false) {
            return $next($request);
        }

        if (!Admin::user() || $this->shouldPassThrough($request)) {
            return $next($request);
        }

        if (Admin::user()->canAccess($request->route())) {
            return $next($request);
        }

        if (!$request->pjax() && $request->ajax()) {
            return $this->response()->error(trans('admin.deny'))->send();
        }

        Pjax::respond(
            response(Admin::content()->withError(trans('admin.deny')))
        );
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        return collect(config('admin.auth.excepts', []))
            ->map('admin_base_path')
            ->contains(function ($except) use ($request) {
                if ($except !== '/') {
                    $except = trim($except, '/');
                }

                return $request->is($except);
            });
    }
}
