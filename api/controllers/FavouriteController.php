<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/24
 * Time: 上午11:45
 */

namespace api\controllers;

use yii;
use api\libs\BaseApi;
use common\models\Favourites;
use yii\helpers\ArrayHelper;
use api\libs\Message;
use common\models\Applicants;

class FavouriteController extends BaseApi{
    public  $modelClass='common\models\Favourites';
    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(),[
            'favourite'=>['Post'],
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['create'],$actions['update'],$actions['view']);
        return $actions;
    }
    /*
     * 用户收藏列表
     */
    public function actionIndex(){
        $params=yii::$app->request->getQueryParams();
        if(empty($params['applicant_id'])){
            return Message::say(Message::E_ERROR,null,"用户id不能为空");
        }
        $model= new  $this->modelClass;
        $query = $model->find()->where(["applicant_id"=>$params['applicant_id']]);

        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    /*
     * 用户收藏 & 取消收藏
     */
    public function actionFavourite(){
        $params=yii::$app->request->getBodyParams();
        $flag=$params['flag'];
        $recruitment_id=$params['recruitment_id'];
        $applicant_id=$params['applicant_id'];
        if(!isset($flag)){
            return Message::say(Message::E_ERROR,null,"字段错误");
        }
        if(empty($recruitment_id)){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        if(empty($applicant_id)){
            return Message::say(Message::E_ERROR,null,"用户id不能为空");
        }
        //先找出该用户、该招聘是否有收藏过
//        $model=new Favourites();
        $favourite=Favourites::find()->andWhere(
            'applicant_id=:aID',[':aID'=>$applicant_id]
        )->andWhere(
            'recruitment_id=:rID',[':rID'=>$recruitment_id]
        )->one();
//        return Message::say(Message::E_OK,$favourite,"ok");
        //判断用户是要收藏，还是要取消
        if($flag==0){
            //取消收藏
            if($favourite!==null){
                $favourite->delete();
                return Message::say(Message::E_OK,null,"取消收藏");
            }
            return Message::say(Message::E_ERROR,null,"取消失败");
        }
        else if($flag==1){
            //添加收藏
            if($favourite){
                return Message::say(Message::E_ERROR,null,"不能重复收藏");
            }
            $model=new $this->modelClass;
            $model->applicant_id=$applicant_id;
            $model->recruitment_id=$recruitment_id;
            if($model->save()){
                return Message::say(Message::E_OK,$model,"收藏成功");
            }
            return Message::say(Message::E_ERROR,null,"收藏失败");
        }
        else{
            return Message::say(Message::E_ERROR,null,"操作失败");
        }
    }
} 