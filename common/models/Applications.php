<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_applications".
 *
 * @property integer $application_id
 * @property integer $business_id
 * @property integer $recruitment_id
 * @property integer $applicant_id
 * @property string $application_video
 * @property string $application_text
 * @property string $application_pic
 * @property integer $application time
 * @property integer $application_sys_state
 * @property integer $application_state
 */
class Applications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_applications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'recruitment_id', 'applicant_id'], 'required'],
            [['business_id', 'recruitment_id', 'applicant_id', 'application time', 'application_sys_state', 'application_state'], 'integer'],
            [['application_video', 'application_text'], 'string', 'max' => 100],
            [['application_pic'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'application_id' => '应聘id',
            'business_id' => '商家id',
            'recruitment_id' => '商家招聘id',
            'applicant_id' => '求职者id',
            'application_video' => '视频应聘路径',
            'application_text' => '文字应聘',
            'application_pic' => '图片应聘路径，可多张',
            'application time' => '应聘时间',
            'application_sys_state' => '系统审核应聘状态：0正常，1审核中，2审核未通过',
            'application_state' => '应聘状态：－1应聘者删除，0商家暂无回应，1商家要求面试 ，2被商家直接录取',
        ];
    }
}
