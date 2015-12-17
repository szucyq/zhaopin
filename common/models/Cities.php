<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_cities".
 *
 * @property integer $city_id
 * @property string $city_name
 * @property string $city_zipcode
 * @property integer $province_id
 * @property integer $city_created
 * @property integer $city_updated
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id'], 'required'],
            [['city_id', 'province_id', 'city_created', 'city_updated'], 'integer'],
            [['city_name', 'city_zipcode'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'city_name' => 'City Name',
            'city_zipcode' => 'City Zipcode',
            'province_id' => 'Province ID',
            'city_created' => 'City Created',
            'city_updated' => 'City Updated',
        ];
    }
}
