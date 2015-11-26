<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2014/12/10
 * Time: 20:21
 */

namespace api\libs;


use yii\web\ErrorAction;
use yii;
use yii\web\HttpException;
use yii\base\Exception;
use yii\base\UserException;
class Error extends ErrorAction{
    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }
        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }
        return [
            'name' => $name,
            'message' => $message,
            'exception' => $exception,
        ];
    }
} 