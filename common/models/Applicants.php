<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_applicants".
 *
 * @property integer $applicant_id
 * @property string $applicant_nickname
 * @property string $applicant_icon
 * @property integer $applicant_sex
 * @property integer $applicant_birthday
 * @property string $applicant_edu
 * @property string $applicant_intro
 * @property string $applicant_latitude
 * @property string $applicant_longitude
 * @property string $applicant_precision
 * @property integer $applicant_concern_id
 */
class Applicants extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_applicants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['applicant_sex', 'applicant_birthday', 'applicant_concern_id'], 'integer'],
            [['applicant_nickname'], 'string', 'max' => 15],
            [['applicant_icon', 'applicant_latitude', 'applicant_longitude', 'applicant_precision'], 'string', 'max' => 100],
            [['applicant_edu'], 'string', 'max' => 30],
            [['applicant_intro'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'applicant_id' => '用户id',
            'applicant_nickname' => '昵称',
            'applicant_icon' => '用户头像',
            'applicant_sex' => '性别，0男，1女2保密',
            'applicant_birthday' => '出生日期',
            'applicant_edu' => '学历',
            'applicant_intro' => '简介',
            'applicant_latitude' => '地理位置纬度',
            'applicant_longitude' => '地理位置经度',
            'applicant_precision' => '地理位置精度',
            'applicant_concern_id' => '用户兴趣统计id',
        ];
    }
}
