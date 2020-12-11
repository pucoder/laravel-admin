<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Auth\Permission as Checker;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Permission
{
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
            abort(403, trans('admin.deny'));
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
