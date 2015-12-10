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
 * @property integer $admin_state
 */
class Admin extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_ACTIVE = 0;
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
            [['admin_sex', 'admin_birthday', 'admin_state'], 'integer'],
            [['admin_username',  'admin_email', 'admin_nick_name'], 'string', 'max' => 30],
            [['admin_icon','admin_pwd'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'admin_username' => 'Admin Username',
            'admin_pwd' => 'Admin Pwd',
            'admin_email' => 'Admin Email',
            'admin_nick_name' => 'Admin Nick Name',
            'admin_icon' => 'Admin Icon',
            'admin_sex' => 'Admin Sex',
            'admin_birthday' => 'Admin Birthday',
            'admin_state' => 'Admin State',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['admin_id' => $id, 'admin_state' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['admin_username' => $username, 'admin_state' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'admin_state' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->admin_pwd);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
