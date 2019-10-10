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
                WxMiddlewareLoginUser::updateOrCreate(['openid'=>$result['user']['openid']],$result['user']);
            }catch (Exception $exception){}
        }elseif(isset($result['openid'])){
            /*保存登录信息*/
            session(['wx_openid'=>$result['openid']]);
        }else{
            exit('<h2>授权配置不正确！</h2>');
        }
        /*返回回调地址*/
        $current_url = session('wx_login_callback_url');
        return redirect($current_url);

    }

    public function get_user_info(Request $request)
    {

    }
}