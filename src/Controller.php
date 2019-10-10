<?php
/**
 * Created by PhpStorm.
 * User: lsshu
 * Date: 2019/10/10
 * Time: 15:12
 */

namespace Lsshu\LaravelWxMiddlewareLogin;

use Illuminate\Http\Request;
use Lsshu\LaravelWxMiddlewareLogin\models\WxMiddlewareLoginUser;
use Lsshu\Wechat\Service;
class Controller
{
    use StoreTrait;

    /**
     * 授权登录
     * @param Request $request
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authorize_login(Request $request)
    {
        $callback_url = $request->input('callback_url',null);
        $snsapi_type = $request->input('snsapi_type','snsapi_base');
        if($callback_url){
            /*记录回调地址*/
            session(['wx_login_callback_url'=>$callback_url]);

            $config = [
                'appId'=>env('ACCOUNT_APPID',''),
                'appSecret'=>env('ACCOUNT_APPSECRET','')
            ];
            $account = Service::account($config);
            $redirect =$account->getAuthorizeBaseInfo(route('wx.middleware.authorize.callback'), $snsapi_type);
            return redirect($redirect);
        }
        return response()->json(['status'=>'error','txt'=>'callback_url is null']);

    }

    /**
     * 微信基本登录回调
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authorize_callback(Request $request)
    {
        $config = [
            'appId'=>env('ACCOUNT_APPID',''),
            'appSecret'=>env('ACCOUNT_APPSECRET','')
        ];
        $account = Service::account($config);
        $data = $request->all();
        /*获取openid*/
        $result = $account->getAuthorizeUserOpenId($data['code']);
        if(isset($result['scope']) && $result['scope'] == 'snsapi_userinfo'){
            $result = $account->getAuthorizeUserInfoByAccessToken($result);
            try{
                $wx = WxMiddlewareLoginUser::updateOrCreate(['openid'=>$result['user']['openid']],$result['user']);
            }catch (Exception $exception){}
        }elseif(isset($result['openid'])){
            /*保存登录信息*/
            try{
                $wx = WxMiddlewareLoginUser::updateOrCreate(['openid'=>$result['openid']]);
            }catch (Exception $exception){}
        }else{
            exit('<h2>授权配置不正确！</h2>');
        }
        /*返回回调地址*/
        $current_url = session('wx_login_callback_url');

        $url_arr = parse_url($current_url);

        if(isset($url_arr['query'])){
            $arr_query = convertUrlQuery($url_arr['query']);
            $arr_query = array_merge($arr_query,['wx_token'=>$wx->getRouteKey()]);
            $current_url = $url_arr['scheme'].'://'.$url_arr['host'].$url_arr['path'].'?'.getUrlQuery($arr_query);
        }else{
            $current_url = $current_url . '?' . 'wx_token='. $wx->getRouteKey();
        }
        return redirect($current_url);
    }

    /**
     * 获取登录用户信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse| \Lsshu\LaravelWxMiddlewareLogin\models\WxMiddlewareLoginUser
     */
    public function get_user_info(Request $request)
    {
        $wx_token = $request->input('wx_token',null);
        if($wx_token){
            $id = hashids_decode($wx_token,'wx_token');
            return WxMiddlewareLoginUser::find(current($id));
        }
        return response()->json(['status'=>'error','txt'=>'wx_token is null']);
    }
}