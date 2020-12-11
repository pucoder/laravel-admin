<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        User::truncate();
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => trans('admin.super_administrator'),
            'permissions' => [],
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name'        => trans('admin.super_administrator'),
            'slug'        => 'administrator',
            'permissions' => ["*"],
        ]);

        // add role to user.
        User::first()->roles()->save(Role::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => trans('admin.home'),
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => trans('admin.administration'),
                'icon'      => 'fa-tasks',
                'uri'       => '',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => trans('admin.admin_users'),
                'icon'      => 'fa-users',
                'uri'       => 'admin_users',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => trans('admin.admin_roles'),
                'icon'      => 'fa-user',
                'uri'       => 'admin_roles',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => trans('admin.admin_menus'),
                'icon'      => 'fa-bars',
                'uri'       => 'admin_menus',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => trans('admin.admin_logs'),
                'icon'      => 'fa-history',
                'uri'       => 'admin_logs',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
