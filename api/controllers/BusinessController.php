<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/21
 * Time: 上午11:49
 */


namespace api\controllers;

use yii;
use api\libs\Message;
use api\libs\BaseApi;
use common\models\Business;
use common\models\Recruitments;

class BusinessController extends BaseApi{
    public $modelClass='common\models\Business';
    protected function verbs()
    {
        return [
            'update'=>['Post'],

        ];
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
        return $actions;
    }

//    public function actionIndex(){
//
//        return Message::say(Message::E_OK,null,"index");
//    }
    public function actionUpdate(){
        $params=yii::$app->request->getBodyParams();
        if(empty($params['business_id'])){
            return Message::say(Message::E_ERROR,null,"用户id不能为空");
        }
        $model = $this->modelClass;
        $business = $model::findOne(yii::$app->getRequest()->post('business_id'));

        if($params['business_name']!=null){
            $business->business_name=$params['business_name'];
        }
        if($business->save()){
            return Message::say(Message::E_OK,$business,"修改成功");
        }
        else{
            return Message::say(Message::E_ERROR,null,"修改失败");
        }
    }

}