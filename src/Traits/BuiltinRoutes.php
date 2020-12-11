<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Http\Controllers\AuthController;
use Encore\Admin\Http\Controllers\MenuController;
use Encore\Admin\Http\Controllers\UserController;

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
            'as'         => config('admin.route.as').'.',
        ];

        app('router')->group($attributes, function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $authController = config('admin.auth.controller', AuthController::class);
            $router->get('login', $authController.'@getLogin')->name('login');
            $router->post('login', $authController.'@postLogin')->name('login_post');
            $router->get('logout', $authController.'@getLogout')->name('logout');
            $router->get('self_setting', $authController.'@getSetting')->name('self_setting');
            $router->put('self_setting', $authController.'@putSetting')->name('self_setting_put');

            $userController = config('admin.database.users_controller', UserController::class);
            $router->resource('admin_users', $userController)->names('admin_users');
            $router->put('admin_users/{admin_user}/restore', $userController.'@restore')->name('admin_users.restore');
            $router->delete('admin_users/{admin_user}/delete', $userController.'@delete')->name('admin_users.delete');

            $menuController = config('admin.database.menus_controller', MenuController::class);
            $router->resource('admin_menus', $menuController)->except(['create'])->names('admin_menus');
            $router->put('admin_menus/{admin_user}/restore', $menuController.'@restore')->name('admin_menus.restore');
            $router->delete('admin_menus/{admin_user}/delete', $menuController.'@delete')->name('admin_menus.delete');

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('Encore\Admin\Http\Controllers')->group(function ($router) {
                /* @var \Illuminate\Routing\Router $router */
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
