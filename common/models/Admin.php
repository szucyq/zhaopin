<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_admin".
 *
 * @property integer $admin_id
 * @property string $admin_account
 * @property string $admin_pwd
 * @property integer $admin_mobile
 * @property string $admin_email
 * @property string $admin_nickname
 * @property string $admin_icon
 * @property string $admin_role
 * @property string $admin_authorities
 * @property integer $admin_state
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_account'], 'required'],
            [['admin_mobile', 'admin_state'], 'integer'],
            [['admin_account', 'admin_email', 'admin_nickname'], 'string', 'max' => 30],
            [['admin_pwd', 'admin_icon', 'admin_role', 'admin_authorities'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'admin_account' => 'Admin Account',
            'admin_pwd' => 'Admin Pwd',
            'admin_mobile' => 'Admin Mobile',
            'admin_email' => 'Admin Email',
            'admin_nickname' => 'Admin Nickname',
            'admin_icon' => 'Admin Icon',
            'admin_role' => 'Admin Role',
            'admin_authorities' => 'Admin Authorities',
            'admin_state' => 'Admin State',
        ];
    }
}
