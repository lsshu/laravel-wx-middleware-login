<?php

namespace Lsshu\LaravelWxMiddlewareLogin\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxMiddlewareLoginUser extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
