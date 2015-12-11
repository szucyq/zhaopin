<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "zp_admin".
 *
 * @property integer $admin_id
 * @property string $admin_username
 * @property string $admin_pwd
 * @property string $admin_email
 * @property string $admin_nick_name
 * @property string $admin_icon
 * @property integer $admin_sex
 * @property integer $admin_birthday
 * @property integer $auth_key
 * @property string $admin_access_token
 * @property integer $admin_state
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zp_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_username', 'admin_pwd'], 'required'],
            [['admin_sex', 'admin_birthday', 'auth_key', 'admin_state'], 'integer'],
            [['admin_username', 'admin_email', 'admin_nick_name'], 'string', 'max' => 30],
            [['admin_pwd', 'admin_icon'], 'string', 'max' => 100],
            [['admin_access_token'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => Yii::t('app', '管理员id'),
            'admin_username' => Yii::t('app', '管理员账号'),
            'admin_pwd' => Yii::t('app', '密码'),
            'admin_email' => Yii::t('app', '邮箱'),
            'admin_nick_name' => Yii::t('app', '昵称'),
            'admin_icon' => Yii::t('app', '头像'),
            'admin_sex' => Yii::t('app', '性别，0男，1女 2保密'),
            'admin_birthday' => Yii::t('app', '出生日期'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'admin_access_token' => Yii::t('app', 'Admin Access Token'),
            'admin_state' => Yii::t('app', '状态，－1删除，0正常，1禁用，2待审核，3拒绝'),
        ];
    }
}
