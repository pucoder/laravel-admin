<?php

namespace Encore\Admin\Middleware;

use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LogOperation
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
        if ($this->shouldLogOperation($request)) {
            $request->setTrustedProxies(request()->getClientIps(), Request::HEADER_X_FORWARDED_FOR);
            $logModel = config('admin.database.logs_model');
            $input = $this->desensitization($request->input());
            $queryString = str_replace('_pjax=%23pjax-container', '', $request->getQueryString());

            try {
                $logModel::create([
                    'user_id' => Admin::user()->id,
                    'operate' => admin_restore_route($request->route()->action['as']),
                    'path'    => substr(admin_restore_path($request->path() . ($queryString ? '?'.$queryString : '')), 0, 255),
                    'method'  => $request->method(),
                    'ip'      => $request->getClientIp(),
                    'input'   => json_encode($input),
                ]);
            } catch (\Exception $exception) {
                // pass
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldLogOperation(Request $request)
    {
        return config('admin.operation_log.enable') && Admin::user() && !$this->inExceptArray($request) && $this->inAllowedMethods($request->method()) && $this->checkPermissions($request);
    }

    /**
     * Whether requests using this method are allowed to be logged.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function inAllowedMethods($method)
    {
        $allowedMethods = collect(config('admin.operation_log.allowed_methods'))->filter();

        if ($allowedMethods->isEmpty()) {
            return true;
        }

        return $allowedMethods->map(function ($method) {
            return strtoupper($method);
        })->contains($method);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (config('admin.operation_log.except') as $except) {
            $except = admin_base_path($except);
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            $methods = [];

            if (Str::contains($except, ':')) {
                list($methods, $except) = explode(':', $except);
                $methods = explode(',', $methods);
            }

            $methods = array_map('strtoupper', $methods);

            if ($request->is($except) &&
                (empty($methods) || in_array($request->method(), $methods))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $request
     * @return bool
     */
    protected function checkPermissions(Request $request)
    {
        return !config('admin.check_permissions') || (config('admin.check_permissions') && Admin::user()->canAccess($request->route()));
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function desensitization($input)
    {
        foreach (config('admin.operation_log.desensitization', []) as $value) {
            if (isset($input[$value])) {
                $input[$value] = '******';
            }
        }

        return $input;
    }
}
