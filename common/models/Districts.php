<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_districts".
 *
 * @property integer $district_id
 * @property string $district_name
 * @property integer $city_id
 * @property integer $district_created
 * @property integer $district_updated
 */
class Districts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_districts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id'], 'required'],
            [['district_id', 'city_id', 'district_created', 'district_updated'], 'integer'],
            [['district_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'district_id' => 'District ID',
            'district_name' => 'District Name',
            'city_id' => 'City ID',
            'district_created' => 'District Created',
            'district_updated' => 'District Updated',
        ];
    }
}
