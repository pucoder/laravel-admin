<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('admin.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->text('permissions')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('admin.database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->text('permissions')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('admin.database.menus_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('admin.database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.logs_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('operate');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.users_table'));
        Schema::dropIfExists(config('admin.database.roles_table'));
        Schema::dropIfExists(config('admin.database.menus_table'));
        Schema::dropIfExists(config('admin.database.role_users_table'));
        Schema::dropIfExists(config('admin.database.logs_table'));
    }
}
