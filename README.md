# 基于laravel-admin的改版，欢迎体验和使用，并提出各种意见

改动如下
---------

- 自动生成完全基于路由的权限控制
- 管理员选择角色时，可配置最大角色数量
- 优化路由的路径和别名命名
- 本地化支持更友好
- 优化配置文件（自定义配置更强）
- 优化样式和用户体验
- 对软删除的支持（增加恢复和强制删除操作）
- 实现模型树操作按钮与表格一样（需要图标配置）
- 实现模型树编辑时可只编辑指定字段，可配置最大层级关系，可配置默认折叠
- 实现头部导航条操作按钮与表格一样（需要图标配置）
- 编辑器已恢复可使用且优化了关系模式下的错误
- 优化文件上传，多文件可以删除，排序，新增同时操作
- 表单开关组件，单选组件，多选组件支持操作后脚本`->changeAfter()`

关于action配置
---------------

```php
// 用户复制action配置示例
// 一，设置路由，开启授权控制
$router->post('users/{user}/replicate', 'UserController@replicate')->name('users.replicate');
  
// 二，设置方法
use Encore\Admin\Controllers\AdminController;
use App\User;
  
class UserController  extends AdminController
{
  public function replicate($id)
  {
      // 方法一，在这里执行逻辑（推荐使用，安全性高）
      try {
          $model = User::find($id);
          DB::transaction(function () use ($model) {
              $model->replicate()->save();
          });
      } catch (\Exception $exception) {
          return $this->response()->error("复制失败: {$exception->getMessage()}")->send();
      }

      return $this->response()->success('复制成功')->refresh()->send();

      // 方法二，去操作类中执行逻辑（存在安全风险，可未授权执行逻辑）
      return $this->handleAction();
  }
}

// 三，设置操作类
namespace App\Admin\Actions;
    
use Encore\Admin\Actions\RowAction;
//use Encore\Admin\Actions\TreeAction;// 模型树操作请继承此类
//use Encore\Admin\Actions\NavAction;// 头部导航条操作请继承此类
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
    
class Replicate extends RowAction
{
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return '复制';
    }

    /**
     * 如果是模型树和头部导航条操作，需要此方法
     * @return string
     */
    //protected function icon()
    //{
    //    return 'fa-bars';
    //}

    /**
     * 如果没有此方法，将不会有权限的控制
     * @return string
     */
    public function getHandleRoute()
    {
        // 这里配置路由的路径
        return "{$this->getResource()}/{$this->getKey()}/replicate";
    }

    /**
     * 这是方法二的逻辑
     * @param Model $model
     *
     * @return \Encore\Admin\Actions\Response
     */
    //public function handle(Model $model)
    //{
    //    try {
    //        DB::transaction(function () use ($model) {
    //            $model->replicate()->save();
    //        });
    //    } catch (\Exception $exception) {
    //        return $this->response()->error("复制失败: {$exception->getMessage()}");
    //    }
    //
    //    return $this->response()->success("复制成功")->refresh();
    //}

    /**
     * @return void
     */
    public function dialog()
    {
        $this->question('确认复制？', '', ['confirmButtonColor' => '#d33']);
    }
}
```

<p align="center">
<a href="https://laravel-admin.org/">
<img src="https://laravel-admin.org/images/logo002.png" alt="laravel-admin">
</a>

<p align="center">⛵<code>laravel-admin</code> is administrative interface builder for laravel which can help you build CRUD backends just with few lines of code.</p>

<p align="center">
<a href="https://laravel-admin.org/docs">Documentation</a> |
<a href="https://laravel-admin.org/docs/zh">中文文档</a> |
<a href="https://demo.laravel-admin.org">Demo</a> |
<a href="https://github.com/z-song/demo.laravel-admin.org">Demo source code</a> |
<a href="#extensions">Extensions</a>
</p>

Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 5.5.0
 - Fileinfo PHP Extension

Installation
------------

> This package requires PHP 7+ and Laravel 5.5, for old versions please refer to [1.4](https://laravel-admin.org/docs/v1.4/#/)

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require pucoder/laravel-admin
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
```
After run command you can find config file in `config/admin.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install.
```
php artisan admin:install
```

Open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

Configurations
------------
The file `config/admin.php` contains an array of configurations, you can find the default configurations in there.

License
------------
`laravel-admin` is licensed under [The MIT License (MIT)](LICENSE).
