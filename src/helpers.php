<?php
/**
 * Created by PhpStorm.
 * User: lsshu
 * Date: 2019/10/10
 * Time: 15:35
 */

if (!function_exists('hashids_decode')) {

    /**
     * Laravel Hashids 解密
     * @param $string
     * @param string $connection
     * @return mixed
     */
    function hashids_decode($string, $connection = 'main') {
        return \Hashids::connection($connection)->decode($string);
    }
}
if (!function_exists('hashids_encode')) {

    /**
     * Laravel Hashids 加密
     * @param $id
     * @param string $connection
     * @return mixed
     */
    function hashids_encode($id,$connection = 'main') {
        return \Hashids::connection($connection)->encode($id);
    }
}
if(!function_exists('fileCache')){
    /**
     * 读写缓存文件
     * @param string $file 文件
     * @param string|array $content 内容
     * @param string $mode 缓存方式 serialize export
     * @return bool|int
     */
    function fileCache($file, $content=null, $mode = 'serialize'){
        if($content===null){
            if(file_exists($file)){
                if($mode == 'export'){
                    return require $file ?? '';
                }
                if($mode == 'serialize'){
                    $handle=fopen($file,'r');
                    return unserialize(fread($handle,filesize($file))) ?? '';
                }
            }
            return [];
        }else{
            // 文件不可写
            if(false === fopen($file,'w+')){return false;}
            // 内容 是否为数组
            if(is_array($content)){
                if($mode == 'export'){
                    $content="<?php\n\rreturn ".var_export($content,true).';';
                }
                if($mode == 'serialize'){
                    $content = serialize($content);
                }
            }
            return file_put_contents($file,$content);//写入缓存
        }
    }
}
if(!function_exists('cacheFile')){
    /**
     * 设置或者获取缓存
     * @param $key
     * @param null $value
     * @param null $file
     * @param string $mode
     * @return bool|int|string
     */
    function cacheFile($key, $value=null, $file=null, $mode = 'serialize')
    {
        if($file === null){$file=('./tmp/cache_file.php');}
        if($value === null){// 获取文件内容
            $cache = fileCache($file,null,$mode);
            return $cache[$key] ?? '';
        }else{// 写入内容
            $cache = fileCache($file,null,$mode);
            $cache[$key] = $value;
            return fileCache($file,$cache,$mode);
        }
    }
}
if(!function_exists('cacheFileDel')){
    /**
     * 删除 缓存
     * @param $key
     * @param null $value
     * @param null $file
     * @param string $mode
     * @return bool|int|string
     */
    function cacheFileDel($key, $file=null, $mode = 'serialize')
    {
        if($file === null){$file=('./tmp/cache_file.php');}
        $cache = fileCache($file,null,$mode);
        unset($cache[$key]);
        return fileCache($file,$cache,$mode);
    }
}

if(!function_exists('convertUrlQuery')){
    /**
     * 将字符串参数变为数组
     * @param $query
     * @return array array (size=10)
    */
    function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
}

if(!function_exists('getUrlQuery')){
    /**
     * 将参数变为字符串
     * @param $array_query
     * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0&region=0&s=1&page=1' (length=73)
     */
    function getUrlQuery($array_query)
    {
        $tmp = array();
        foreach($array_query as $k=>$param)
        {
            $tmp[] = $k.'='.$param;
        }
        $params = implode('&',$tmp);
        return $params;
    }
}