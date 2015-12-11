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
            [['uid', 'type', 'mobile', 'state', 'create_time'], 'integer'],
            [['username'], 'required'],
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
            'aid' => Yii::t('app', '账号id'),
            'uid' => Yii::t('app', '用户id'),
            'type' => Yii::t('app', '用户类型0求职者，1商家'),
            'username' => Yii::t('app', '用户名'),
            'mobile' => Yii::t('app', '手机号'),
            'email' => Yii::t('app', '邮箱'),
            'openid' => Yii::t('app', '微信openid'),
            'unionid' => Yii::t('app', '微信统一身份id'),
            'access_token' => Yii::t('app', '用户token'),
            'device_token' => Yii::t('app', '设备token'),
            'state' => Yii::t('app', '状态，－1删除，0正常，1禁用，2待审核，3拒绝'),
            'create_time' => Yii::t('app', '注册时间'),
            'remark' => Yii::t('app', '备注'),
        ];
    }

}
