<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/17
 * Time: 下午1:53
 */

namespace api\controllers;

use yii;
use common\models\Categories;
use api\libs\BaseApi;

class CategoryController extends BaseApi{
    public $modelClass='common\models\Categories';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['create'],$actions['update'],$actions['view']);
        return $actions;
    }
    public function actionIndex(){
        $params = yii::$app->getRequest()->getQueryParams();
        if(empty($params['parent_id'])){
            $query = Categories::find()->where(
                ["category_parent_id"=>$params['parent_id']
                ]);
        }


        return new yii\data\ActiveDataProvider([
            'query'=>$query,
            'pagination' => array('pageSize' => 50),
        ]);
    }
} 