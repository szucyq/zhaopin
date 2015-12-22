<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/10/22
 * Time: 上午10:50
 */
namespace api\controllers;

use yii;
use common\models\Accounts;
use api\libs\Message;
use api\libs\BaseApi;
use common\models\Business;
use common\models\Applicants;
use yii\helpers\ArrayHelper;


class AccountsController extends BaseApi {
    public $modelClass = 'common\models\Accounts';
//    public $serializer = 'api\libs\Serializerapi';

//    public $serializer = [
//        'class' => 'yii\rest\Serializer',
//        'collectionEnvelope' => 'items',
//    ];


    protected function verbs()
    {
        return [
            'test' => ['Post'],
            'create'=>['Post'],
            'index'=>['Get'],
            'login'=>['Post'],
            'applicantupdate'=>['Post'],
            'businessupdate'=>['Post'],
        ];
    }
//    public function actions()
//    {
//        $actions = parent::actions();
//        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
//        return $actions;
//    }

    public function actionIndex(){
//        return ["status"=>0,"data"=>"index"];
//        throw new yii\web\UnauthorizedHttpException;
//          throw new \yii\web\NotFoundHttpException;
//        throw new \yii\web\HttpException(401);
//        throw new yii\web\UnauthorizedHttpException;
//        return ["status"=>0,"data"=>"view"];
//        throw new \yii\web\NotFoundHttpException;
    }

    public function actionCreate(){
        $model = new $this->modelClass;
        return ["status"=>0,"data"=>"create"];
    }

    public function actionView($id){
        $model = new $this->modelClass;
        $acc_data = $model::findOne($id);
        if($acc_data == null)
            return Message::say(Message::E_ERROR,null,"数据为空");

        $query=new \yii\db\Query();
        $usertype=$acc_data['acc_type'];
        if($usertype==0){
            //求职者
            $query ->select('a.*,b.*')
                ->from('rc_accounts a')
                ->innerJoin('rc_applicants b','a.acc_userid=b.applicant_id')
                ->where(['a.acc_id'=>$id]);
        }
        else if($usertype==1){
            //商家
            $query ->select('a.*,b.*')
                ->from('rc_accounts a')
                ->innerJoin('rc_business b','a.acc_userid=b.business_id')
                ->where(['a.acc_id'=>$id]);
        }

        return new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

    }


    public function actionApplicantupdate(){

        return Message::say(Message::E_OK,null,"ok");
    }
    public function actionBusinessupdate(){
        return Message::say(Message::E_OK,null,"ok");
    }


}
/*
 * 相关sql方法
 * SELECT * FROM `rc_accounts` a, `rc_applicants` b where a.acc_userid = b.applicant_id
 * SELECT * FROM `rc_accounts` a inner join `rc_applicants` b on a.acc_userid = b.applicant_id
 * SELECT * FROM `rc_accounts` a inner join `rc_applicants` b on a.acc_userid = b.applicant_id  where a.acc_userid=1
 * SELECT a.*,b.* FROM `rc_accounts` a inner join `rc_applicants` b on a.acc_userid = b.applicant_id  where a.acc_userid=1
 */
?>