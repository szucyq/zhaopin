<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-10
 * Time: 下午3:52
 */

namespace api\controllers;

use Faker\Provider\Base;
use api\libs\BaseApi;
use api\libs\Message;
use yii;
use api\libs\Sms;

class SmsController extends BaseApi{
    public $modelClass = '';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['delete'],$actions['update'],$actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if($this->action->id == 'code' || $this->action->id == 'vcode'){
            unset($behaviors['authenticator']);
        }
        return $behaviors;
    }

//    protected function verbs()
//    {
//        return [
//            'code' => ['Get'],
//            'vcode'=>['Get'],
//        ];
//    }
//    public function actions()
//    {
//        $actions = parent::actions();
//        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
//        return $actions;
//    }

    public function actionCode()
    {
        $mobile = Yii::$app->request->getQueryParam('mobile');
        if(!empty($mobile)){
            $sms = new Sms();
            return $sms->getVerifyCode($mobile);
		}else{
            throw new yii\base\InvalidParamException("请填写手机号码mobile");
        }
    }

    public function actionVcode()
    {
        $code = yii::$app->getRequest()->get('code');
        $mobile = yii::$app->getRequest()->get('mobile');
        $sms = new Sms();
        return $sms->checkVerifyCode($mobile,$code);
    }

    public function actionQunfa()
    {
        $type = yii::$app->getRequest()->getQueryParam('type');
        $data = yii::$app->getRequest()->getBodyParams();
        $mobiles = explode(',',$data['mobiles']);
        $mobiles = array_chunk($mobiles,100);
        $reason = $data['reason'];
        $startime = $data['startime'];
        $endtime = $data['endtime'];
        $where = $data['where'];
        $from = $data['from'];

        $sms = new Sms();
        switch($type){
          //停电
          case 'td':
            $sms->tempid = "MB-2015011907";
            break;
          //停水
          case 'ts':
            $sms->tempid = "MB-2015011948";
            break;
          //电梯维修
          case 'dtwx':
            $sms->tempid = "MB-2015011928";
            break;
          default:
            $sms->tempid = "";
            break;
            }
        $template = '@1@=%s,@2@=%s,@3@=%s,@4@=%s,@5@=%s';
        $content = sprintf($template,$reason,$where,$startime,$endtime,$from);
        static $error = 0;
        foreach($mobiles as $m){
            $mobile = implode(',',$m);
            $return = $sms->post(['mobile'=>$mobile,'content'=>$content]);
            $error += $return['error'];
        }
        return Message::say(Message::E_OK,$error,"发送成功");
    }
}