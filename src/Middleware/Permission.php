<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class Permission
{
    /**
     * @var string
     */
    protected $middlewarePrefix = 'admin.permission:';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $args
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$args)
    {
//        dd(get_routes());
//        dd(set_permissions());
//        dd(group_permissions());
//        dd(request()->route());
        if (config('admin.check_route_permission') === false) {
            return $next($request);
        }

        if ((!$user = Admin::user()) || !empty($args) || $this->shouldPassThrough($request)) {
            return $next($request);
        }

        if ($user->canAccess($request->route())) {
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
