<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Controllers\AuthController;
use Encore\Admin\Controllers\LogController;
use Encore\Admin\Controllers\MenuController;
use Encore\Admin\Controllers\RoleController;
use Encore\Admin\Controllers\UserController;

trait HasRoutes
{
    /**
     * Register the laravel-admin builtin routes.
     *
     * @return void
     *
     * @deprecated Use Admin::routes() instead();
     */
    public function registerAuthRoutes()
    {
        $this->routes();
    }

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
            /* @var \Illuminate\Routing\Router $router */
            $authController = config('admin.auth.auth_controller', AuthController::class);
            $router->get('login', $authController.'@getLogin')->name('login');
            $router->post('login', $authController.'@postLogin')->name('login_post');
            $router->get('logout', $authController.'@getLogout')->name('logout');
            $router->get('self_setting', $authController.'@getSetting')->name('self_setting');
            $router->put('self_setting', $authController.'@putSetting')->name('self_setting_put');

            $userController = config('admin.database.users_controller', UserController::class);
            $router->resource('admin_users', $userController)->names('admin_users');
            $router->put('admin_users/{admin_user}/restore', $userController.'@restore')->name('admin_users.restore');
            $router->delete('admin_users/{admin_user}/delete', $userController.'@delete')->name('admin_users.delete');

            $roleController = config('admin.database.roles_controller', RoleController::class);
            $router->resource('admin_roles', $roleController)->names('admin_roles');
            $router->put('admin_roles/{admin_role}/restore', $roleController.'@restore')->name('admin_roles.restore');
            $router->delete('admin_roles/{admin_role}/delete', $roleController.'@delete')->name('admin_roles.delete');

            $menuController = config('admin.database.menus_controller', MenuController::class);
            $router->resource('admin_menus', $menuController, ['except' => ['create']])->names('admin_menus');
            $router->put('admin_menus/{admin_menu}/restore', $menuController.'@restore')->name('admin_menus.restore');
            $router->delete('admin_menus/{admin_menu}/delete', $menuController.'@delete')->name('admin_menus.delete');

            $logController = config('admin.database.logs_controller', LogController::class);
            $router->resource('admin_logs', $logController, ['only' => ['index', 'destroy']])->names('admin_logs');

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('\Encore\Admin\Controllers')->group(function ($router) {
                /* @var \Illuminate\Routing\Router $router */
                $router->post('_handle_form_', 'HandleController@handleForm')->name('handle_form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('handle_action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('handle_selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('handle_renderable');
            });
        });
    }
}
