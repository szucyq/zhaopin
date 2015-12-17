<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/10/22
 * Time: 上午10:50
 */
namespace api\controllers;
use common\models\Accounts;
use yii\rest\ActiveController;
use yii;
use api\libs\Message;

use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;


class AccountsController extends ActiveController {
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
        ];
    }
//    public function actions()
//    {
//        $actions = parent::actions();
//        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
//        return $actions;
//    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                #这个地方使用`ComopositeAuth` 混合认证
                'class' => CompositeAuth::className(),
                #`authMethods` 中的每一个元素都应该是 一种 认证方式的类或者一个 配置数组
                'authMethods' => [
                    HttpBasicAuth::className(),
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ]
            ]
        ]);
    }



    public function actionIndex(){
//        return ["status"=>0,"data"=>"index"];
    }

    public function actionCreate(){
        $model = new $this->modelClass;
        return ["status"=>0,"data"=>"create"];
    }

    public function actionView($id){
        return ["status"=>0,"data"=>"view"];
    }
    public function actionTest(){
//        echo 'haha';
        return ["status"=>0,"data"=>"test"];
    }
    public function actionTt(){
//        echo 'haha';
        return ["status"=>0,"data"=>"tt"];
    }
    public function actionData(){
        return ['status'=>0,"data"=>'data'];
    }
    /**
     * login
     * if first login ,auto create an account
     *
     */
    public function actionLogin(){
        return ;
    }
}

?>