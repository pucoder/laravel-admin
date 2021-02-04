<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Http\Controllers\AuthController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

trait BuiltinRoutes
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
            'as'         => config('admin.route.as') . '.',
        ];

        app('router')->group($attributes, function ($router) {
            $authController = config('admin.auth.controller', AuthController::class);
            /* @var Router $router */
            $router->get('login', $authController.'@login')->name('login');
            $router->post('login', $authController.'@postLogin')->name('login_post');
            $router->get('logout', $authController.'@logout')->name('logout');
            $router->get('auth_setting', $authController.'@setting')->name('auth_setting');
            $router->put('auth_setting', $authController.'@putSetting')->name('auth_setting_put');

            /* @var Route $router */
            $router->namespace('\Encore\Admin\Http\Controllers')->group(function ($router) {
                /* @var Router $router */
                $router->resource('auth_users', 'UserController')->names('auth_users');
                $router->resource('auth_menus', 'MenuController', ['except' => ['create']])->names('auth_menus');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');

                // requirejs配置
                $router->get('_require_config', 'PagesController@requireConfig')->name('require_config');

                $router->fallback('PagesController@error404')->name('error404');
            });
        });
    }
}
