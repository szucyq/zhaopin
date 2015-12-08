<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zp_accounts".
 *
 * @property integer $aid
 * @property integer $uid
 * @property integer $type
 * @property string $username
 * @property integer $mobile
 * @property string $email
 * @property string $openid
 * @property string $unionid
 * @property string $access_token
 * @property string $device_token
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
            [['uid', 'type', 'username'], 'required'],
            [['uid', 'type', 'mobile', 'state', 'create_time'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['email', 'remark'], 'string', 'max' => 30],
            [['openid', 'unionid', 'access_token', 'device_token'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'uid' => 'Uid',
            'type' => 'Type',
            'username' => 'Username',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'access_token' => 'Access Token',
            'device_token' => 'Device Token',
            'state' => 'State',
            'create_time' => 'Create Time',
            'remark' => 'Remark',
        ];
    }
}
