<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_favourites".
 *
 * @property integer $favourite_id
 * @property integer $applicant_id
 * @property integer $recruitment_id
 */
class Favourites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_favourites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_id', 'recruitment_id'], 'required'],
            [['applicant_id', 'recruitment_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'favourite_id' => '收藏id',
            'applicant_id' => '求职者id',
            'recruitment_id' => '招聘id',
        ];
    }
}
