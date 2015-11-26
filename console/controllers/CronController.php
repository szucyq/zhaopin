<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-22
 * Time: 上午11:06
 */

namespace console\controllers;


use common\libs\Jack;
use common\models\Community;
use common\models\Device;
use api\libs\Sms;
use common\models\Jobs;
use common\models\Staff;
use common\models\User;
use common\models\Xgpoint;
use common\models\Xjpoint;
use common\models\Xcpoint;
use yii;
use yii\log;
use common\models\Devicerecord;
use common\models\Schedulelog;
use common\models\Scheduleplan;
use common\models\Scheduleplandetail;
use common\models\Schedulereal;

use common\models\Xckqlog;
use common\models\Xcpbplan;
use common\models\Xcpbplandetail;
use common\models\XcpbCurrent;

use common\models\Xjkqlog;
use common\models\Xjpbplan;
use common\models\Xjpbplandetail;
use common\models\XjpbCurrent;
use yii\console\Controller;
use common\libs\Push;

/**
 * 定时任务脚本.
 * @package console\controllers
 */
class CronController extends Controller{

    /**
     * @var int
     */
    public $color = 1;

    /**
     * 设备管理后台任务
     */
    public function actionIndex(){
        $nowtime = date("Y-m-d",time());
        $devices = Device::find()->where("RepairTime>='$nowtime'")->orWhere("CheckTime>='$nowtime'")->orWhere("ScrapTime>='$nowtime'")->all();
        foreach($devices as $device){
            $noticeRepairtime = date("Y-m-d",strtotime($device->RepairTime)-$device->WarnDays*24*60*60);
            $noticeChecktime = date("Y-m-d",strtotime($device->CheckTime)-$device->WarnDays*24*60*60);
            $noticeScraptime = date("Y-m-d",strtotime($device->ScrapTime)-$device->WarnDays*24*60*60);
            $community = Community::findOne($device->CommunityID);
            if($noticeRepairtime == $nowtime){
                $this->sendSms($device->Phone,$device->Charger,$community->CommunityName,$device->DeviceName,$device->RepairTime,$device->ID,"维修");
            }
            if($noticeChecktime == $nowtime){
                $this->sendSms($device->Phone,$device->Charger,$community->CommunityName,$device->DeviceName,$device->RepairTime,$device->ID,"巡检");
            }
            if($noticeScraptime == $nowtime){
                $this->sendSms($device->Phone,$device->Charger,$community->CommunityName,$device->DeviceName,$device->RepairTime,$device->ID,"报废");
            }
        }
    }

    /**
     * 巡更排班后台任务
     */
    public function actionSchedule(){
        $nowtime = date("Y-m-d H:i",time()).":00";
        $gotime = date("Y-m-d H:i",time()).":59";
        $scheduleplans = Scheduleplan::find()->where('State=0')->andWhere("EffectiveTime>='$nowtime'")
            ->andWhere("EffectiveTime<='$gotime'")->all();
        foreach($scheduleplans as $scheduleplan){
            $plandetails = Scheduleplandetail::find()->where("SchedulePlanID=".$scheduleplan['ID'])->all();
            foreach($plandetails as $plandetail){
                Schedulereal::deleteAll([
                    "StaffID"=>$plandetail->StaffID,
                    "Days"=>$plandetail->Days,
                ]);
			}
			foreach($plandetails as $plandetail){
                $planreal = new Schedulereal();
                $planreal->StaffID = $plandetail->StaffID;
                $planreal->XgPointID = $plandetail->XgPointID;
                $planreal->BeginTime = $plandetail->BeginTime;
                $planreal->EndTime = $plandetail->EndTime;
                $planreal->Days = $plandetail->Days;
                $planreal->RecordTime = $plandetail->RecordTime;
                $planreal->CommunityID = $plandetail->CommunityID;
                if($planreal->save(false)){
                    $plandetail->State = 1;
                    $scheduleplan->State = 1;
                    $plandetail->save(false);
                    $scheduleplan->save(false);
                }else{
                    yii::log("Schedule ERROR");
                }
            }
        }
    }

    /**
     * 巡查排班后台任务
     */
    public function actionXcschedule(){
        $nowtime = date("Y-m-d H:i",time()).":00";
        $gotime = date("Y-m-d H:i",time()).":59";
        $scheduleplans = Xcpbplan::find()->where('State=0')->andWhere("EffectiveTime>='$nowtime'")
            ->andWhere("EffectiveTime<='$gotime'")->all();
        foreach($scheduleplans as $scheduleplan){
            $plandetails = Xcpbplandetail::find()->where("SchedulePlanID=".$scheduleplan['ID'])->all();
            foreach($plandetails as $plandetail){
                XcpbCurrent::deleteAll([
                    "StaffID"=>$plandetail->StaffID,
                    "Days"=>$plandetail->Days,
                ]);
            }
            foreach($plandetails as $plandetail){
                $planreal = new XcpbCurrent();
                $planreal->StaffID = $plandetail->StaffID;
                $planreal->XgPointID = $plandetail->XgPointID;
                $planreal->BeginTime = $plandetail->BeginTime;
                $planreal->EndTime = $plandetail->EndTime;
                $planreal->Days = $plandetail->Days;
                $planreal->RecordTime = $plandetail->RecordTime;
                $planreal->CommunityID = $plandetail->CommunityID;
                if($planreal->save(false)){
                    $plandetail->State = 1;
                    $scheduleplan->State = 1;
                    $plandetail->save(false);
                    $scheduleplan->save(false);
                }else{
                    yii::log("Schedule ERROR");
                }
            }
        }
    }

    /**
     * 巡检排班后台任务
     */
    public function actionXjschedule(){
        $nowtime = date("Y-m-d H:i",time()).":00";
        $gotime = date("Y-m-d H:i",time()).":59";
        $scheduleplans = Xjpbplan::find()->where('State=0')->andWhere("EffectiveTime>='$nowtime'")
            ->andWhere("EffectiveTime<='$gotime'")->all();
        foreach($scheduleplans as $scheduleplan){
            $plandetails = Xjpbplandetail::find()->where("SchedulePlanID=".$scheduleplan['ID'])->all();
            foreach($plandetails as $plandetail){
                XjpbCurrent::deleteAll([
                    "StaffID"=>$plandetail->StaffID,
                    "Days"=>$plandetail->Days,
                ]);
            }
            foreach($plandetails as $plandetail){
                $planreal = new XjpbCurrent();
                $planreal->StaffID = $plandetail->StaffID;
                $planreal->XgPointID = $plandetail->XgPointID;
                $planreal->BeginTime = $plandetail->BeginTime;
                $planreal->EndTime = $plandetail->EndTime;
                $planreal->Days = $plandetail->Days;
                $planreal->RecordTime = $plandetail->RecordTime;
                $planreal->CommunityID = $plandetail->CommunityID;
                if($planreal->save(false)){
                    $plandetail->State = 1;
                    $scheduleplan->State = 1;
                    $plandetail->save(false);
                    $scheduleplan->save(false);
                }else{
                    yii::log("Schedule ERROR");
                }
            }
        }
    }

	 /**
     * 巡更记录后台任务
     */
    public function actionSchedulelog($t=false)
    {
        if(!$t){
            $tday = date("w",strtotime("+1 days"))+1;
        }else{
            $tday = date("w",strtotime("+1 days"));
        }
        $reals = Schedulereal::find()->where("Days=$tday")->all();
        foreach($reals as $real){
            $slog = new Schedulelog();
            $slog->StaffID = $real->StaffID;
            $slog->XgPointID = $real->XgPointID;
            $slog->BeginTime = $real->BeginTime;
            $slog->EndTime = $real->EndTime;
			$slog->Days = $real->Days;
            $slog->xgTime = date("Y-m-d",strtotime("+1 days"))." 00:00:00";
            $slog->CommunityID = $real->CommunityID;
			if(!$slog->save(false)){
				yii::log("Schedulelog ERROR");
			}
        }

    }

    /**
     * 巡检记录后台任务
     */
    public function actionXjschedulelog($t=false)
    {
        if(!$t){
            $tday = date("w",strtotime("+1 days"))+1;
        }else{
            $tday = date("w",strtotime("+1 days"));
        }
        $reals = XjpbCurrent::find()->where("Days=$tday")->all();
        foreach($reals as $real){
            $slog = new Xjkqlog();
            $slog->StaffID = $real->StaffID;
            $slog->XgPointID = $real->XgPointID;
            $slog->BeginTime = $real->BeginTime;
            $slog->EndTime = $real->EndTime;
            $slog->Days = $real->Days;
            $slog->xgTime = date("Y-m-d",strtotime("+1 days"))." 00:00:00";
            $slog->CommunityID = $real->CommunityID;
            if(!$slog->save(false)){
                yii::log("Schedulelog ERROR");
            }
        }

    }

    /**
     * 巡查记录后台任务
     */
    public function actionXckqlog($t=false)
    {
        if(!$t){
            $tday = date("w",strtotime("+1 days"))+1;
        }else{
            $tday = date("w",strtotime("+1 days"));
        }
        $reals = XcpbCurrent::find()->where("Days=$tday")->all();
        foreach($reals as $real){
            $slog = new Xckqlog();
            $slog->StaffID = $real->StaffID;
            $slog->XgPointID = $real->XgPointID;
            $slog->BeginTime = $real->BeginTime;
            $slog->EndTime = $real->EndTime;
            $slog->Days = $real->Days;
            $slog->xgTime = date("Y-m-d",strtotime("+1 days"))." 00:00:00";
            $slog->CommunityID = $real->CommunityID;
            if(!$slog->save(false)){
                yii::log("Schedulelog ERROR");
            }
        }

    }

    /**
     * 巡更漏巡通知
     */
    public function actionNoticemiss()
    {
        $nowdate = date("Y-m-d H:i:s",time());
        $start = date("Y-m-d")." 00:00:00";
        $end = $nowdate;
        $jack = new Jack();
        $miss = $jack->getMissSchedulelog($start,$end,[],false);
        foreach($miss as $m){
            $output = "%s，%s－%s，%s，有漏巡（请假、未上传...）记录，请及时处理";
            $staff = Staff::findOne($m->StaffID);
            $boss = Staff::find()->where(['JobID'=>Jobs::find()->where(['DepartID'=>$staff->DepartId,'IsBoss'=>1])->one()->ID])->one();
            $bossuser = User::find()->select('device_token')->where(['TypeID'=>2,'ObjectID'=>$boss->ID])->one();
            $xgpoint = Xgpoint::findOne($m->XgPointID);
            $user = User::find()->select('device_token')->where([
                'TypeID'=>2,
                'ObjectID'=>$m->StaffID
            ])->one();
            $data = sprintf($output,$staff->RealName,$m->BeginTime,$m->EndTime,$xgpoint->XGPointName);
            $content = [
                 "ticker"=>"你有新的消息哦",
                 "title"=>"巡更提醒",
                 "text"=>$data,
                 "after_open"=>Push::GO_ACTIVITY,
                 "activity"=>"com.wtw.xunfang.activity.AlarmActivity"
            ];
            $push = Push::getOwn();
            $push->type(Push::SEND_TYPE_LISTCAST,null,[$user->device_token,$bossuser->device_token]);
            $result = $push->send($content);
            if(json_decode($result)->ret != 'SUCCESS'){
                Yii::error("Noticemiss ERROR");
            }
        }
    }

    /**
     * 巡检漏巡通知
     */
    public function actionXjnoticemiss()
    {
        $nowdate = date("Y-m-d H:i:s",time());
        $start = date("Y-m-d")." 00:00:00";
        $end = $nowdate;
        $jack = new Jack();
        $miss = $jack->getXjMissSchedulelog($start,$end,[],false);
        foreach($miss as $m){
            $output = "%s，%s－%s，%s，有漏巡（请假、未上传...）记录，请及时处理";
            $staff = Staff::findOne($m->StaffID);
            $boss = Staff::find()->where(['JobID'=>Jobs::find()->where(['DepartID'=>$staff->DepartId,'IsBoss'=>1])->one()->ID])->one();
            $bossuser = User::find()->select('device_token')->where(['TypeID'=>2,'ObjectID'=>$boss->ID])->one();
            $xgpoint = Xjpoint::findOne($m->XgPointID);
            $user = User::find()->select('device_token')->where([
                'TypeID'=>2,
                'ObjectID'=>$m->StaffID
            ])->one();
            $data = sprintf($output,$staff->RealName,$m->BeginTime,$m->EndTime,$xgpoint->XGPointName);
            $content = [
                "ticker"=>"你有新的消息哦",
                "title"=>"巡检提醒",
                "text"=>$data,
                "after_open"=>Push::GO_ACTIVITY,
                "activity"=>"com.wtw.xunjian.activity.AlarmActivity"
            ];
            $push = Push::getOwn('xj');
            $push->type(Push::SEND_TYPE_LISTCAST,null,[$user->device_token,$bossuser->device_token]);
            $result = $push->send($content);
            if(json_decode($result)->ret != 'SUCCESS'){
                Yii::error("Noticemiss ERROR");
            }
        }
    }

    /**
     * 巡查漏巡通知
     */
    public function actionXcnoticemiss()
    {
        $nowdate = date("Y-m-d H:i:s",time());
        $start = date("Y-m-d")." 00:00:00";
        $end = $nowdate;
        $jack = new Jack();
        $miss = $jack->getXcMissSchedulelog($start,$end,[],false);
        foreach($miss as $m){
            $output = "%s，%s－%s，%s，有漏巡（请假、未上传...）记录，请及时处理";
            $staff = Staff::findOne($m->StaffID);
            $boss = Staff::find()->where(['JobID'=>Jobs::find()->where(['DepartID'=>$staff->DepartId,'IsBoss'=>1])->one()->ID])->one();
            $bossuser = User::find()->select('device_token')->where(['TypeID'=>2,'ObjectID'=>$boss->ID])->one();
            $xgpoint = Xcpoint::findOne($m->XgPointID);
            $user = User::find()->select('device_token')->where([
                'TypeID'=>2,
                'ObjectID'=>$m->StaffID
            ])->one();
            $data = sprintf($output,$staff->RealName,$m->BeginTime,$m->EndTime,$xgpoint->XGPointName);
            $content = [
                "ticker"=>"你有新的消息哦",
                "title"=>"巡查提醒",
                "text"=>$data,
                "after_open"=>Push::GO_ACTIVITY,
                "activity"=>"com.wtw.xuncha.activity.AlarmActivity"
            ];
            $push = Push::getOwn('xc');
            $push->type(Push::SEND_TYPE_LISTCAST,null,[$user->device_token,$bossuser->device_token]);
            $result = $push->send($content);
            if(json_decode($result)->ret != 'SUCCESS'){
                Yii::error("Noticemiss ERROR");
            }
        }
    }

    /**
     * @param $mobile
     * @param $charger
     * @param $communityid
     * @param $devicename
     * @param $time
     * @param $id
     * @param $type
     */

    public function sendSms($mobile,$charger,$communityid,$devicename,$time,$id,$type)
    {
        $sms = new Sms();
        $sms->tempid = 'MB-2014122246';
        $template = '@1@=%s,@2@=%s,@3@=%s,@4@=%s,@5@=%s';
        $content = sprintf($template,$charger,$communityid,$devicename,$time,$type);
        $data = ['mobile'=>$mobile,'content'=>$content];
        $return = $sms->post($data);
        if($return['error'] == 0){
            $devicrecord = new DeviceRecord();
            $devicrecord->TypeName = $type;
            $devicrecord->DeviceId = $id;
            $device = Device::findOne($id);
            $devicrecord->Describes = $device->Describes !== null ? $device->Describes : $content;
            $devicrecord->Reason = '系统定时提醒';
            $devicrecord->DisposeTime = $time;
            $devicrecord->StaffName = $charger;
            $devicrecord->CommunityId = $device->CommunityID;
            $devicrecord->save(false);
        }
    }
    public function actionCyq(){
        echo "hhaasdfafasf\n";
//        print_r("aa");
//        $this->stdout("Hello?\n", Console::BOLD);
        return 1;
    }

}