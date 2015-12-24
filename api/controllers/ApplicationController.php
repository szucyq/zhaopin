<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/23
 * Time: 下午3:31
 */

namespace api\controllers;

use api\libs\Message;
use common\models\Applications;
use yii;
use api\libs\BaseApi;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ApplicationController extends BaseApi {
    public $modelClass = 'common\models\Applications';

    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(),[
            'video'=>['Post'],
            'pic' => ['Post'],
            'view'=>['get'],
            'add'=>['post'],
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['create'],$actions['update'],$actions['view']);
        return $actions;
    }
    /*
     * 用户应聘列表
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
     * 用户应聘详情
     */
    public function actionView($id){
//        return Message::say(Message::E_ERROR,null,"应聘id不能为空");
        if(empty($id)){
            return Message::say(Message::E_ERROR,null,"应聘id不能为空");
        }
        $model=new $this->modelClass;
        $query=$model->find()->where(["application_id"=>$id]);
        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    /*
     * 删除应聘
     */
    public function actionDelete(){
        $params=yii::$app->request->getBodyParams();
        if(empty($params['application_id'])){
            return Message::say(Message::E_ERROR,null,"应聘id不能为空");
        }
        $model = Applications::find()->where('application_id=:id', [
            ':id'=>$params['application_id']
        ])->one();

        if ($model== null) {
//            throw new NotFoundHttpException('您访问的事件没有找到');
            return Message::say(Message::E_ERROR,null,"该应聘不存在");

        } else {
            $model->delete();
            return Message::say(Message::E_OK,null,"删除成功");
        }



    }

    /*
     * 用户提交图片应聘
     */
    public function actionPic(){
        $params=yii::$app->request->getBodyParams();

        $yearname = date('Y',time());
        $monthname = date('m',time());

        //去除头部
        $arr=explode(',',$params['application_pic']);  //去头
        $value=count($arr)>1?$arr[1]:$arr[0];//截取图片实际内容部分

        $basePath=yii::$app->params['appPicPath'];
        $yearpath =$basePath .$yearname;
        $monthpath = $yearpath."/".$monthname;
        //判断目录是否存在
        if(!is_dir($yearpath)){
            mkdir($yearpath,0777,true);
        }
        if(!is_dir($monthpath)){
            mkdir($monthpath,0777,true);
        }

        //创建图片
        $picname = $yearname.'/'.$monthname.'/'.md5($value).'.jpg';
        $image = $basePath . $picname;
        if(!file_exists($image)){
            if(!file_put_contents($image,base64_decode($value))){
                return  Message::say(Message::E_ERROR,null,"图片上传失败");
            }
            return Message::say(Message::E_OK,$picname,"ok");
        }
        return Message::say(Message::E_OK,$picname,"ok");

    }
    /*
     * 用户提交视频应聘
     */
    public function actionVideo()
    {
        $file = yii\web\UploadedFile::getInstanceByName('application_video');
        if(!$file){
            return Message::say(Message::E_ERROR,null,"参数错误");
        }
        $yearname = date('Y',time());
        $monthname = date('m',time());
        $basePath=yii::$app->params['appVideoPath'];
        $yearpath =$basePath .$yearname;
        $monthpath = $yearpath."/".$monthname;
        //判断目录是否存在
        if(!is_dir($yearpath)){
            mkdir($yearpath,0777,true);
        }
        if(!is_dir($monthpath)){
            mkdir($monthpath,0777,true);
        }


        $ext = $file->getExtension();
        $filename = $yearname.'/'.$monthname.'/'.time() . rand(1000, 9999) . "." . $ext;

        if($file->saveAs($basePath . $filename,false)){
            return Message::say(Message::E_OK,$basePath. $filename,'OK');
        }else{
            return Message::say(Message::E_ERROR,null,'上传失败');
        }
    }

    /*
     * 用户添加一条应聘
     */
    public function actionCreate(){
        $params=yii::$app->request->getBodyParams();
        if(empty($params['business_id'])){
            return Message::say(Message::E_ERROR,null,"商家id不能为空");
        }
        if(empty($params['applicant_id'])){
            return Message::say(Message::E_ERROR,null,"求职者id不能为空");
        }
        if(!isset($params['recruitment_id'])){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        if(empty($params['application_text'])){
            return Message::say(Message::E_ERROR,null,"应聘内容不能为空");
        }





        $model=new $this->modelClass;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($model->validate() && $model->save()){
            return Message::say(Message::E_OK,$model,"发布应聘成功");
        }
        else{
            return Message::say(Message::E_ERROR,null,"发布应聘失败");
        }
    }
} 