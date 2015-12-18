<?php
namespace api\controllers;

use common\models\Accounts;
use common\models\Applicants;
use common\models\Business;
use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use api\models\LoginForm;
use yii\filters\VerbFilter;
use api\libs\SMS;
use api\libs\Message;
use yii\web\Response;



/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'logout' => ['POST'],
            'reg' => ['POST'],
            'index'=>['Get'],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'api\libs\Error',
            ],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
//    public function actions()
//    {
//        $actions = parent::actions();
//        unset($actions['index'],$actions['login'],$actions['delete'], $actions['create'],$actions['update']);
//        return $actions;
//    }

    public function actionIndex()
    {
        return "ok";
    }

    public function actionLogin()
    {
        $data = array();
        $params = Yii::$app->request->getBodyParams();
        if(isset($params['acc_device_token'])){
            $devisetoken = $params['acc_device_token'];
        }else{
            $devisetoken = "";
        }

        $sms = new Sms();
        $vcode = $sms->checkVerifyCode($params['mobile'], $params['code'])['error'];
        if ($vcode == 0) {
            $userdata = Accounts::get_user_by_mobile($params['mobile']);
            if(empty($userdata)){
                $access_token=Yii::$app->security->generateRandomString();

                $this->userReg($params['mobile'],$devisetoken,$access_token);
                $userdata = Accounts::get_user_by_mobile($params['mobile']);
                if(!$userdata){
                    return Message::say(Message::E_ERROR,null,"登录错误");
                }
            }

            return Message::say(Message::E_OK,$userdata,"登录成功");
        }else{
            return Message::say(Message::E_ERROR,null,"验证码错误");
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /*
     * 使用手机号进行注册
     */
    public  function userReg($mobile,$devisetoken,$access_token){
        $params = Yii::$app->request->getBodyParams();
        $acc_type=$params['type'];
        if($acc_type==0){
            //求职人员
            $model=new Applicants();
            $model->applicant_nickname=$mobile;
            if($model->validate() && $model->save()){
                if ($this->saveAccount($model))
                {
                    return $model;
                } else {
                    $model->delete();
                }
            }
        }
        else if($acc_type==1){
            //商家
            $model=new Business();
            $model->business_name=$mobile;
            $model->business_contactor_mobile=$mobile;
            $model->province_id=$params['province_id'];
            $model->province_name=$params['province_name'];
            $model->city_id=$params['city_id'];
            $model->city_name=$params['city_name'];
            $model->district_id=$params['district_id'];
            $model->district_name=$params['district_name'];
            $model->district_detail=$params['district_detail'];

            if($model->validate() && $model->save()){
                if ($this->saveAccount($model))
                {
                    return $model;
                } else {
                    $model->delete();
                }
            }
            else{
                return Message::say(Message::E_ERROR,null,"字段错误");
            }
        }
        return null;
    }
    /*
     * 根据用户注册id保存账号
     */
    public function saveAccount($model){
//        $params = yii::$app->request->queryParams;
        $params = Yii::$app->request->getBodyParams();
        $account=new Accounts();
        $account->acc_mobile=$params['mobile'];
        $account->acc_access_token=Yii::$app->security->generateRandomString();
        $account->acc_type=$params['type'];
        $account->acc_state=0;
        $acc_type=$params['type'];
        if($acc_type==0){
            $account->acc_userid=$model->applicant_id;
        }
        else if ($acc_type==1){
            $account->acc_userid=$model->business_id;
        }

        if($account->validate() && $account->save())
        {
            return true;
        }else{
            return false;
        }
    }
}
