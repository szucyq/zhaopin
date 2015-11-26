<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zp_accounts".
 *
 * @property integer $id
 * @property integer $mobile
 * @property string $email
 * @property string $name
 * @property string $nick_name
 * @property string $icon
 * @property integer $sex
 * @property integer $birthday
 * @property string $openid
 * @property string $unionid
 * @property string $user_token
 * @property string $latitude
 * @property string $longitude
 * @property string $device_token
 * @property string $position_precision
 * @property integer $state
 * @property integer $create_time
 * @property string $remark
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zp_accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mobile', 'email', 'name', 'nick_name', 'icon', 'sex', 'birthday', 'openid', 'unionid', 'user_token', 'latitude', 'longitude', 'device_token', 'position_precision', 'state', 'create_time', 'remark'], 'required'],
            [['id', 'mobile', 'sex', 'birthday', 'state', 'create_time'], 'integer'],
            [['email', 'name', 'nick_name', 'remark'], 'string', 'max' => 30],
            [['icon', 'openid', 'unionid', 'user_token', 'latitude', 'longitude', 'device_token', 'position_precision'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'name' => 'Name',
            'nick_name' => 'Nick Name',
            'icon' => 'Icon',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'user_token' => 'User Token',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'device_token' => 'Device Token',
            'position_precision' => 'Position Precision',
            'state' => 'State',
            'create_time' => 'Create Time',
            'remark' => 'Remark',
        ];
    }
}
