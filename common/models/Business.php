<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_business".
 *
 * @property integer $business_id
 * @property string $business_name
 * @property string $business_contactor
 * @property integer $business_contactor_mobile
 * @property string $business_contactor_tel
 * @property string $business_intro
 * @property string $business_icon
 * @property string $business_album
 * @property string $business_latitude
 * @property string $business_longitude
 * @property string $business_altitude
 * @property string $business_precision
 * @property integer $business_position_lock
 * @property integer $province_id
 * @property string $province_name
 * @property integer $city_id
 * @property string $city_name
 * @property integer $district_id
 * @property string $district_name
 * @property string $district_detail
 */
class Business extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_position_lock', 'province_id', 'city_id', 'district_id'], 'integer'],
            [['province_id', 'province_name', 'city_id', 'city_name', 'district_id', 'district_name'], 'required'],
            [['business_name', 'business_contactor', 'province_name', 'city_name', 'district_name', 'district_detail'], 'string', 'max' => 30],
            [['business_contactor_mobile'], 'string', 'max' => 11],
            [['business_contactor_tel'], 'string', 'max' => 20],
            [['business_intro'], 'string', 'max' => 200],
            [['business_icon', 'business_latitude', 'business_longitude', 'business_altitude', 'business_precision'], 'string', 'max' => 100],
            [['business_album'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_id' => Yii::t('app', '用户id'),
            'business_name' => Yii::t('app', '商家名'),
            'business_contactor' => Yii::t('app', '商家联系人姓名'),
            'business_contactor_mobile' => Yii::t('app', '商家联系人手机号'),
            'business_contactor_tel' => Yii::t('app', '商家联系人座机号'),
            'business_intro' => Yii::t('app', '商家介绍'),
            'business_icon' => Yii::t('app', '商家头像'),
            'business_album' => Yii::t('app', '一组商家照片'),
            'business_latitude' => Yii::t('app', '地理位置纬度'),
            'business_longitude' => Yii::t('app', '地理位置经度'),
            'business_altitude' => Yii::t('app', '海拔高度'),
            'business_precision' => Yii::t('app', '地理位置精度'),
            'business_position_lock' => Yii::t('app', '锁定位置'),
            'province_id' => Yii::t('app', '省id'),
            'province_name' => Yii::t('app', '省名'),
            'city_id' => Yii::t('app', '城市id'),
            'city_name' => Yii::t('app', '城市名'),
            'district_id' => Yii::t('app', '区县id'),
            'district_name' => Yii::t('app', '区县名'),
            'district_detail' => Yii::t('app', '商家详细地址'),
        ];
    }
}
