<?php
/**
 * Created by PhpStorm.
 * User: bruce
 * Date: 15/12/9
 * Time: 下午3:50
 */
namespace console\controllers;
use common\models\Admin ;
use Yii;
class InitController extends \yii\console\Controller
{
    /**
     * Create init user
     */
    public function actionAdmin()
    {
        echo "创建一个新用户 ...\n";                  // 提示当前操作
        $username = $this->prompt('User Name:');        // 接收用户名
        $password = $this->prompt('Password:');         // 接收密码
        $password_hash = Yii::$app->security->generatePasswordHash($password);
        $model = new Admin();                            // 创建一个新用户
        $model->admin_username = $username;                   // 完成赋值
        $model->admin_pwd = $password_hash;
        if (!$model->save())                            // 保存新的用户
        {
            foreach ($model->getErrors() as $error)     // 如果保存失败，说明有错误，那就输出错误信息。
            {
                foreach ($error as $e)
                {
                    echo "$e\n";
                }
            }
            return 1;                                   // 命令行返回1表示有异常
        }
        return 0;                                       // 返回0表示一切OK
    }
}