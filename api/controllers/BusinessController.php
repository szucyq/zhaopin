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
use common\models\Applications;

class BusinessController extends BaseApi{
    public $modelClass='common\models\Business';
    protected function verbs()
    {
        return [
            'update'=>['Post'],
            'recruitmentlist'=>['get'],
            'recruitmentdetail'=>['get'],
            'recruitmentupdate'=>['post'],
            'recruitmentstate'=>['post'],
            'applicationdetail'=>['get'],
            'applicationstate'=>['post'],
        ];
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['view'],$actions['delete'], $actions['create'],$actions['update']);
        return $actions;
    }
    /*
     * 商家修改资料
     */
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

    /*
     * 商家提交商家自身的图片资料
     */
    public function actionPic(){
        $params=yii::$app->request->getBodyParams();

        $yearname = date('Y',time());
        $monthname = date('m',time());

        //去除头部
        $arr=explode(',',$params['business_pic']);  //去头
        $value=count($arr)>1?$arr[1]:$arr[0];//截取图片实际内容部分

        $basePath=yii::$app->params['rcIconPath'];
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
     * 商家查看已发招聘列表
     */
    public function actionRecruitmentlist(){
        $params=yii::$app->request->getQueryParams();
        if(empty($params['business_id'])){
            return Message::say(Message::E_ERROR,null,"商家id不能为空");
        }

        $query = Recruitments::find()->where(["business_id"=>$params['business_id']]);

        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    /*
     * 商家修改已发招聘
     */
    public function actionRecruitmentupdate(){
        $params=yii::$app->request->getBodyParams();
        if(!isset($params['recruitment_id'])){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        $recruitment=Recruitments::findOne($params['recruitment_id']);
        if($recruitment==null){
            return Message::say(Message::E_ERROR,null,"该招聘不存在");
        }
        //判断修改字段是否合法
        if(empty($params['category_id'])){
            return Message::say(Message::E_ERROR,null,"招聘栏目id不能为空");
        }
        if(!isset($params['recruitment_type'])){
            return Message::say(Message::E_ERROR,null,"招聘类型不能为空");
        }
        if(empty($params['recruitment_text'])){
            return Message::say(Message::E_ERROR,null,"招聘内容不能为空");
        }

//        $recruitment->load(Yii::$app->getRequest()->getBodyParams(), '');
        $recruitment->business_id=$params['business_id'];
        $recruitment->recruitment_type=$params['recruitment_type'];
        $recruitment->category_id=$params['category_id'];
        $recruitment->recruitment_state=0;
        $recruitment->recruitment_text=$params['recruitment_text'];
        if($recruitment->validate() && $recruitment->save()){
            return Message::say(Message::E_OK,$recruitment,"修改招聘成功");
        }
        else{
            return Message::say(Message::E_ERROR,null,"修改招聘失败");
        }

    }
    /*
     * 商家查看招聘详情，需返回应聘信息
     */
    public function actionRecruitmentdetail(){
        $params=yii::$app->request->getQueryParams();
        if(empty($params['recruitment_id'])){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        $query=Recruitments::find()->where(["recruitment_id"=>$params['recruitment_id']]);
        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    /*
     * 商家查看某招聘应聘情况
     *
     */
    public function actionApplicationdetail(){
        $params=yii::$app->request->getQueryParams();
        if(empty($params['recruitment_id'])){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }

        $query=Applications::find()->where(["recruitment_id"=>$params['recruitment_id']]);
        return new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
    }
    /*
     * 商家修改某招聘状态
     */
    public function actionRecruitmentstate(){
        $params=yii::$app->request->getBodyParams();
        if(!isset($params['recruitment_id'])){
            return Message::say(Message::E_ERROR,null,"招聘id不能为空");
        }
        if(!isset($params['recruitment_state'])){
            return Message::say(Message::E_ERROR,null,"字段不能为空");
        }
        $recruitment=Recruitments::findOne($params['recruitment_id']);
        if($recruitment==null){
            return Message::say(Message::E_ERROR,null,"该招聘不存在");
        }
        $recruitment->recruitment_state=$params['recruitment_state'];
        if($recruitment->save()){
            return Message::say(Message::E_OK,null,"修改成功");
        }
        return Message::say(Message::E_ERROR,null,"修改失败");
    }
    /*
     * 商家修改某应聘状态
     */
    public function actionApplicationstate(){
        $params=yii::$app->request->getBodyParams();
        if(!isset($params['application_id'])){
            return Message::say(Message::E_ERROR,null,"应聘id不能为空");
        }
        if(!isset($params['application_state'])){
            return Message::say(Message::E_ERROR,null,"应聘状态不能为空");
        }
        $application=Applications::findOne($params['application_id']);
        if($application==null){
            return Message::say(Message::E_ERROR,null,"该应聘不存在");
        }
        $application->application_state=$params['application_state'];
        if($application->save()){
            return Message::say(Message::E_OK,null,"修改成功");
        }
        return Message::say(Message::E_ERROR,null,"修改失败");
    }
}