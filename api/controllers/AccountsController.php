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


class AccountsController extends ActiveController {
    public $modelClass = 'common\models\Accounts';


    protected function verbs()
    {
        return [
            'test' => ['Post'],
            'create'=>['Post'],
            'index'=>['Get'],
        ];
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
        return $actions;
    }




    public function actionIndex(){
        return ["status"=>0,"data"=>"index"];
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
}

?>