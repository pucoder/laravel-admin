<?php


namespace Encore\Admin\Traits;


use Encore\Admin\Controllers\AuthController;

trait DefaultRoute
{
    /**
     * Register the laravel-admin builtin routes.
     *
     * @return void
     */
    public function routes()
    {
        $attributes = [
            'prefix'     => config('admin.route.prefix'),
            'middleware' => config('admin.route.middleware'),
            'as' => config('admin.route.as').'.',
        ];

        app('router')->group($attributes, function ($router) {
            /* @var \Illuminate\Support\Facades\Route $router */
            $authController = config('admin.auth.controller', AuthController::class);
            $router->get('auth/setting', $authController.'@getSetting')->name('self_setting');
            $router->put('auth/setting', $authController.'@putSetting')->name('self_setting_put');
            $router->get('auth/login', $authController.'@getLogin')->name('login');
            $router->post('auth/login', $authController.'@postLogin')->name('login_post');
            $router->get('auth/logout', $authController.'@getLogout')->name('logout');

            $router->namespace('Encore\Admin\Controllers')->group(function ($router) {
                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'UserController', ['except' => ['show']])->names('auth_users');
                $router->put('auth/users/{user}/restore', 'UserController@restore')->name('auth_users.restore');
                $router->resource('auth/roles', 'RoleController', ['except' => ['show']])->names('auth_roles');
                $router->put('auth/roles/{role}/restore', 'RoleController@restore')->name('auth_roles.restore');
                $router->resource('auth/menus', 'MenuController', ['except' => ['create', 'show']])->names('auth_menus');
                $router->put('auth/menus/{menu}/restore', 'MenuController@restore')->name('auth_menus.restore');
                $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']])->names('auth_logs');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');
            });
        });
    }
}
