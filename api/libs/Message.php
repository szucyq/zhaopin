<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-5
 * Time: 下午3:34
 */

namespace api\libs;


class Message {

    const E_SYSTEM = 1;
    const E_PARAMS = 2;
    const E_POWER = 3;
    const E_VAL = 4; //审核中
    const E_ERROR = 5;
    const E_MISS = 10;  //用户token失效
    const E_DENY = 6; //拒绝
    const E_NO = 7; //禁用
    const E_UNREG = 8; //未注册
    const E_UNBIND = 9; //未绑定
    const E_OK = 0;

    public static function say($code,$data,$message)
    {
        return ['status'=>$code,'data'=>$data,'message'=>$message];
    }
} 