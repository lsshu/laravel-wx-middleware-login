<?php
/**
 * Created by PhpStorm.
 * User: lsshu
 * Date: 2019/10/10
 * Time: 14:59
 */

namespace Lsshu\LaravelWxMiddlewareLogin;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Routing\Router;
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(Router $router)
    {
        $this->registerRoute($router);

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations/wx_middleware')
        ], 'wx-middleware-login-migrations');
    }
    /**
     * Register routes.
     *
     * @param $router
     */
    protected function registerRoute($router)
    {
        if (!$this->app->routesAreCached()) {
            $router->group(['prefix'=>'wx-middleware-login'],function($router){
                $router->group(['namespace' => __NAMESPACE__,'middleware' => 'web'], function ($router) {
                    $router->get('authorize_login','Controller@authorize_login')->name('wx.middleware.authorize.login'); // 授权登录
                    $router->get('authorize_callback','Controller@authorize_callback')->name('wx.middleware.authorize.callback'); // 授权登录回调
                    $router->get('get_user_info','Controller@get_user_info')->name('wx.middleware.get.user.info'); // 获取登录用户信息
                });
            });
        }
    }
}