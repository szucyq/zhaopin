<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_provinces".
 *
 * @property integer $province_id
 * @property string $province_name
 * @property integer $province_created
 * @property integer $province_updated
 */
class Provinces extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_provinces';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id'], 'required'],
            [['province_id', 'province_created', 'province_updated'], 'integer'],
            [['province_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'province_id' => 'Province ID',
            'province_name' => 'Province Name',
            'province_created' => 'Province Created',
            'province_updated' => 'Province Updated',
        ];
    }
}
