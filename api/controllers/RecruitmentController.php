<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/19
 * Time: 下午2:59
 */

namespace api\controllers;

use yii;
use api\libs\BaseApi;
use api\libs\Message;
use common\models\Recruitments;

use yii\helpers\ArrayHelper;

class RecruitmentController extends BaseApi {
    public $modelClass = 'common\models\Recruitments';

    protected function verbs()
    {
        return ArrayHelper::merge(parent::verbs(),[
            'video'=>['Post'],
            'pic' => ['Post'],
        ]);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['delete'], $actions['create'],$actions['update'],$actions['view']);
        return $actions;
    }
    public function actionIndex(){
        $params = yii::$app->getRequest()->getQueryParams();

        if(empty($params['category_id'])){
            return Message::say(Message::E_ERROR,null,"栏目id不能为空");

        }
        else{
            $query = Recruitments::find()->where(["category_id"=>$params['category_id']]);
        }
        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    public function actionView($id){
        if(empty($id)){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        else{
            $query=Recruitments::find()->where(["recruitment_id"=>$id]);
        }

        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);


//        $model = new $this->modelClass;
//        $data = $model::findOne($id);
//        if($data == null)
//            return Message::say(Message::E_ERROR,null,"数据为空");
//        return Message::say(Message::E_OK,$data,"获取成功");

    }
    public function actionCreate(){
        $params=yii::$app->request->getBodyParams();
        if(empty($params['business_id'])){
            return Message::say(Message::E_ERROR,null,"用户id不能为空");
        }
        if(empty($params['category_id'])){
            return Message::say(Message::E_ERROR,null,"招聘栏目id不能为空");
        }
        if(!isset($params['recruitment_type'])){
            return Message::say(Message::E_ERROR,null,"招聘类型不能为空");
        }
        if(empty($params['recruitment_text'])){
            return Message::say(Message::E_ERROR,null,"招聘内容不能为空");
        }

//        $recruitment = new $this->modelClass();
        $recruitment=new Recruitments();
        $recruitment->load(Yii::$app->getRequest()->getBodyParams(), '');

//        $recruitment->business_id=$params['business_id'];
//        $recruitment->recruitment_type=$params['recruitment_type'];
//        $recruitment->category_id=$params['category_id'];
//        $recruitment->recruitment_state=0;
//        $recruitment->recruitment_text=$params['recruitment_text'];

        /* 图片处理 */
//        $yearname = date('Y',time());
//        $monthname = date('m',time());
//
//        //去除头部
//        $arr=explode(',',$params['recruitment_pic']);  //去头
//        $value=count($arr)>1?$arr[1]:$arr[0];//截取图片实际内容部分
//
//        $basePath=yii::$app->params['rcPicPath'];
//        $yearpath =$basePath .$yearname;
//        $monthpath = $yearpath."/".$monthname;
//        //判断目录是否存在
//        if(!is_dir($yearpath)){
//            mkdir($yearpath,0777,true);
//        }
//        if(!is_dir($monthpath)){
//            mkdir($monthpath,0777,true);
//        }
//
//        //创建图片
//        $picname = $yearname.'/'.$monthname.'/'.md5($value).'.jpg';
//        $image = $basePath . $picname;
//        if(!file_exists($image)){
//            if(!file_put_contents($image,base64_decode($value))){
//                Message::say(Message::E_ERROR,null,"图片上传失败");
//            }
//        }
//
//        $recruitment->recruitment_pic=$picname;



//        return Message::say(Message::E_OK,$recruitment,"发布招聘成功");

//        try{
//            $recruitment->save();
//            return Message::say(Message::E_OK,$recruitment,"发布招聘成功1");
//        }
//        catch(Exception $e){
//            return Message::say(Message::E_ERROR,null,"发布招聘失败1");
//        }
        if($recruitment->validate() && $recruitment->save()){
            return Message::say(Message::E_OK,$recruitment,"发布招聘成功");
        }
        else{
            return Message::say(Message::E_ERROR,null,"发布招聘失败");
        }
    }
    /*
     * 图片
     */
    public function actionPic(){
        $params=yii::$app->request->getBodyParams();

        $yearname = date('Y',time());
        $monthname = date('m',time());

        //去除头部
        $arr=explode(',',$params['recruitment_pic']);  //去头
        $value=count($arr)>1?$arr[1]:$arr[0];//截取图片实际内容部分

        $basePath=yii::$app->params['rcPicPath'];
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
     * 视频
     */
    public function actionVideo()
    {
        $file = yii\web\UploadedFile::getInstanceByName('recruitment_video');
        if(!$file){
            return Message::say(Message::E_ERROR,null,"参数错误");
        }
        $yearname = date('Y',time());
        $monthname = date('m',time());
        $basePath=yii::$app->params['rcVideoPath'];
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

} 