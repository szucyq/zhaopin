<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-5
 * Time: 下午2:14
 */

namespace api\libs;


use yii\rest\ActiveController;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\VerbFilter;

class BaseApi extends ActiveController{

    public $serializer = 'api\libs\Serializerapi';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator'=>[
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    MyQueryParamAuth::className(),
                ],
            ],
        ]);
    }


    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['POST','PUT'],
            'delete' => ['DELETE'],
        ];
    }

} 