<?php namespace Wechat\Facades;

use October\Rain\Support\Facade;

class WechatApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wechat.api';
    }
}
