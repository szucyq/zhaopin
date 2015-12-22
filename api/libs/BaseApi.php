<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-5
 * Time: 下午2:14
 */

namespace api\libs;


use yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

use api\libs\Message;

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
//                    MyQueryParamAuth::className(),
//                    HttpBasicAuth::className(),
//                    HttpBearerAuth::className(),
//                    QueryParamAuth::className(),
                ],
            ],
        ]);
    }


//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(), [
//            'authenticator' => [
//                #这个地方使用`ComopositeAuth` 混合认证
//                'class' => CompositeAuth::className(),
//                #`authMethods` 中的每一个元素都应该是 一种 认证方式的类或者一个 配置数组
//                'authMethods' => [
//                    HttpBasicAuth::className(),
//                    HttpBearerAuth::className(),
//                    QueryParamAuth::className(),
//                ]
//            ]
//        ]);
//    }

    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['POST','PUT'],
            'delete' => ['DELETE'],
        ];
    }

} 