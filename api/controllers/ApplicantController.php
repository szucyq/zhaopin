<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/21
 * Time: 上午11:48
 */

namespace api\controllers;

use yii;
use api\libs\Message;
use api\libs\BaseApi;
use common\models\Business;
use common\models\Applicants;

class ApplicantController extends BaseApi{
    public $modelClass='common\models\Applicants';
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
        if(empty($params['applicant_id'])){
            return Message::say(Message::E_ERROR,null,"用户id不能为空");
        }
        $model = $this->modelClass;
        $applicant = $model::findOne(yii::$app->getRequest()->post('applicant_id'));
        $applicant_edu=$params['applicant_edu'];
        if($applicant_edu!=null){
            $applicant->applicant_edu=$applicant_edu;
        }
        if($applicant->save()){
            return Message::say(Message::E_OK,$applicant,"修改成功");
        }
        else{
            return Message::say(Message::E_ERROR,null,"修改失败");
        }
    }

} 