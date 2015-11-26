<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-30
 * Time: 下午1:47
 */

namespace api\libs;


class Response extends \yii\web\Response{
    public $newheaders;

    public function getHeaders()
    {
        $data = parent::getHeaders();
        foreach ($this->newheaders as $k=>$v){
            $data->set($k,$v);
        }
        return $data;
    }
}