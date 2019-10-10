<?php

namespace Lsshu\LaravelWxMiddlewareLogin\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class WxMiddlewareLoginUser extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hashidsConnection = 'wx_token';
    protected $fillable = ['openid','nickname','sex','language','city','province','country','headimgurl'];
    protected $visible = ['openid','nickname','sex','language','city','province','country','headimgurl'];
    /**
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::connection($this->hashidsConnection)->encode($this->id);
    }

    /**
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model|null|void
     */
    public function resolveRouteBinding($value)
    {
        if(!is_numeric($value)){
            $value = current(Hashids::connection($this->hashidsConnection)->decode($value));
            if(!$value){
                return ;
            }
        }
        return $this->where($this->getRouteKeyName(), $value)->first();
    }
}
