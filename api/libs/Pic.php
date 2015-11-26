<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 15-1-15
 * Time: 下午6:05
 */

namespace api\libs;
use yii;


class Pic {

    private $pic;
    private $picname;
    private $picpath;

    public function  __construct($picsrc)
    {
        $this->pic = base64_decode($picsrc);
        $this->picpath = yii::$app->params['image'].'/upload/pic/';
        $this->picname = md5($picsrc).'.jpg';
    }

    public function readPic()
    {
        header('Content-Type:image/jpeg');
        if(!file_exists($this->picpath.$this->picname)){
            return $this->makePic();
        }else{
            return 'http://images.youyoulive.com/api/upload/pic/'.$this->picname;
        }

    }

    public function makePic()
    {
        if(file_put_contents($this->picpath.$this->picname,$this->pic)){
            return 'http://images.youyoulive.com/api/upload/pic/'.$this->picname;
        }
    }
}