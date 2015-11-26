<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-10
 * Time: 下午5:57
 */

namespace api\libs;

use yii;
class Sms {

    const BASEURL =  "http://mssms.cn:8000/msm/sdk/http/sendsmsutf8.jsp";
    public $username = "JSMB260181";
    public $scode = "101321";
    public $tempid = "MB-2013102300";
    public $lifetime = 7200;
    public $maxtime = 60;
    public $content = "";

    function post(array $data,$url=null)
    {
        if($url == null)
            $url = self::BASEURL;
        if(is_array($data)){
            if(!empty($data['content'])){
                $content = $data['content'];
            }else{
                $content = $this->content;
            }
            $params = ['username'=>$this->username,'scode'=>$this->scode,'mobile'=>$data['mobile'],'content'=>$content,'tempid'=>$this->tempid];
            $d = http_build_query($params);
        }
        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $d);
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        $return = explode("#",trim($return));
        $output = ['error'=>$return[0]];
        return $output;
    }

    public function getVerifyCode($mobile)
    {
            $time = yii::$app->getSession()->get($mobile.'authcode.time');
            if(!isset($time) || ($time+$this->maxtime) < time()){
                $code = $this->generateVerifyCode();
                $params = ['mobile'=>$mobile,'content'=>"@1@=".$code];
                $data = $this->post($params);
                if($data['error'] == 0){
                    yii::$app->getSession()->open();
                    yii::$app->getSession()->set($mobile.'authcode',$code);
                    yii::$app->getSession()->set($mobile.'authcode.time',time());
                    yii::$app->getSession()->set($mobile.'authcode.timeout',time()+$this->lifetime);
                }
                return ['error'=>$data['error']];
            }else{
                return ['error'=>1];
            }
    }

    public function checkVerifyCode($mobile,$code)
    {
        yii::$app->getSession()->open();
        $timeout = yii::$app->getSession()->get($mobile.'authcode.timeout');
        if($timeout < time()){
            yii::$app->getSession()->remove($mobile.'authcode');
            yii::$app->getSession()->remove($mobile.'authcode.time');
            yii::$app->getSession()->remove($mobile.'authcode.timeout');
        }
        $authcode = yii::$app->getSession()->get($mobile.'authcode');
        if($code == $authcode && !empty($code)){
            return ['error'=>'0', 'lifetime'=>$timeout-time()];
        }else{
            return ['error'=>'1'];
        }
    }

    protected function generateVerifyCode()
    {
        $letters = [0,1,2,3,4,5,6,7,8,9];
        $len = [0,1,2,3,4];
        $code = "";
        for($i=0;$i<sizeof($len);$i++)
        {
            $code .= $letters[mt_rand(0,9)];
        }
        return $code;
    }

    public static function generatepwd()
    {
        $letters = [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','j','k','i','n','x','m','y','z'];
        $len = [0,1,2,3,4,5];
        $code = "";
        for($i=0;$i<sizeof($len);$i++)
        {
            $code .= $letters[mt_rand(0,25)];
        }
        return $code;
    }
} 