<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_accounts".
 *
 * @property integer $acc_id
 * @property integer $acc_userid
 * @property string $acc_username
 * @property string $acc_pwd
 * @property integer $acc_mobile
 * @property string $acc_email
 * @property string $acc_openid
 * @property string $acc_unionid
 * @property string $acc_access_token
 * @property string $acc_device_token
 * @property integer $acc_type
 * @property integer $acc_state
 * @property integer $acc_disabled_begintime
 * @property integer $acc_disabled_length
 * @property integer $acc_create_time
 * @property string $acc_remark
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acc_userid', 'acc_mobile', 'acc_access_token', 'acc_type', 'acc_state'], 'required'],
            [['acc_userid', 'acc_mobile', 'acc_type', 'acc_state', 'acc_disabled_begintime', 'acc_disabled_length', 'acc_create_time'], 'integer'],
            [['acc_username', 'acc_pwd', 'acc_email', 'acc_remark'], 'string', 'max' => 30],
            [['acc_openid', 'acc_unionid', 'acc_access_token', 'acc_device_token'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'acc_id' => 'Acc ID',
            'acc_userid' => 'Acc Userid',
            'acc_username' => 'Acc Username',
            'acc_pwd' => 'Acc Pwd',
            'acc_mobile' => 'Acc Mobile',
            'acc_email' => 'Acc Email',
            'acc_openid' => 'Acc Openid',
            'acc_unionid' => 'Acc Unionid',
            'acc_access_token' => 'Acc Access Token',
            'acc_device_token' => 'Acc Device Token',
            'acc_type' => 'Acc Type',
            'acc_state' => 'Acc State',
            'acc_disabled_begintime' => 'Acc Disabled Begintime',
            'acc_disabled_length' => 'Acc Disabled Length',
            'acc_create_time' => 'Acc Create Time',
            'acc_remark' => 'Acc Remark',
        ];
    }
}
