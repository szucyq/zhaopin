<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/17
 * Time: 下午1:06
 */

namespace api\controllers;

use yii;

use common\models\Provinces;
use common\models\Cities;
use common\models\Districts;
use api\libs\BaseApi;

class AddressController extends  BaseApi{
    public $modelClass = 'common\models\Provinces';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['create'],$actions['update'],$actions['view']);
        return $actions;
    }

    public function actionIndex(){
//        return "ok";
        $params = yii::$app->getRequest()->getQueryParams();

        if(empty($params['province_id']) && empty($params['province_id'])){
            $query = Provinces::find();
        }
        if(!empty($params['province_id'])){
            $query = Cities::find()->where(['province_id'=>$params['province_id']]);
        }
        if(!empty($params['city_id'])){
            $query = Districts::find()->where(['city_id'=>$params['city_id']]);
        }

        return new yii\data\ActiveDataProvider([
            'query'=>$query,
            'pagination' => array('pageSize' => 50),
        ]);
    }

} 