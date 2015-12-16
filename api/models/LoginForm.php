<?php
namespace api\models;

use Yii;
use yii\base\Model;

use api\libs\Message;
/**
 * Login form
 */
class LoginForm extends Model
{


    public $acc_mobile;
    public $acc_access_token;
    private $_user = false;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['acc_userid', 'acc_mobile', 'acc_access_token', 'acc_type', 'acc_state'], 'required'],
            [['acc_mobile',], 'required'],
            [['acc_userid', 'acc_mobile', 'acc_type', 'acc_state', 'acc_disabled_begintime', 'acc_disabled_length', 'acc_create_time'], 'integer'],
            [['acc_username', 'acc_pwd', 'acc_email', 'acc_remark'], 'string', 'max' => 30],
            [['acc_openid', 'acc_unionid', 'acc_access_token', 'acc_device_token'], 'string', 'max' => 100]
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
//                $this->addError($attribute, 'Incorrect username or password.');

                return Message::say(Message::E_ERROR,"saf","用户名不存在");
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
//        if ($this->validate()) {
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
//        } else {
//            return false;
//        }

        $user = $this->getUser();
        if (!$user) {
            return Message::say(Message::E_ERROR,null,"用户名不存在");
        }

        return Message::say(Message::E_OK,$user,"登录成功");
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
//        if ($this->_user === null) {
//            $this->_user = UserAccount::findByUsermobile($this->acc_mobile);
//        }

        $this->_user = UserAccount::findByUsermobile($this->acc_mobile);
        return $this->_user;


    }
}
