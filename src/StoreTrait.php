<?php
/**
 * Created by PhpStorm.
 * User: lsshu
 * Date: 2019/10/10
 * Time: 15:14
 */

namespace Lsshu\LaravelWxMiddlewareLogin;
use Redis;

trait StoreTrait
{
    /**
     * 保存缓存
     * @param string $key
     * @param string|integer $value
     * @return mixed
     */
    protected function setStore($key,$value)
    {
        if(env('CACHE_DRIVER','file') ==='redis'){
            return Redis::set($key,$value);
        }
        return cacheFile($key,$value,storage_path('logs/cache_file_auth_login.php'),'export');
    }
    /**
     * 获取缓存
     * @param string $key
     * @return mixed
     */
    protected function getStore($key)
    {
        if(env('CACHE_DRIVER','file') ==='redis'){
            return Redis::get($key);
        }
        return cacheFile($key,null,storage_path('logs/cache_file_auth_login.php'),'export');
    }
    /**
     * 删除缓存
     * @param $key
     * @return bool|int|string
     */
    protected function delStore($key)
    {
        if(env('CACHE_DRIVER','file') ==='redis'){
            return Redis::del($key);
        }
        return cacheFileDel($key,storage_path('logs/cache_file_auth_login.php'),'export');
    }
}