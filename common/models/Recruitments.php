<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_recruitments".
 *
 * @property integer $recruitment_id
 * @property integer $business_id
 * @property integer $category_id
 * @property integer $recruitment_type
 * @property string $recruitment_video
 * @property string $recruitment_text
 * @property string $recruitment_pic
 * @property integer $recruitment_number
 * @property integer $recruitment_salary_min
 * @property string $recruitment_requriment
 * @property integer $recruitment_endtime
 * @property integer $recruitment_creattime
 * @property integer $recruitment_top
 * @property integer $recruitment_top_price
 * @property integer $recruitment_top_begintime
 * @property integer $recruitment_top_endtime
 * @property integer $recruitment_sys_state
 * @property integer $recruitment_state
 */
class Recruitments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_recruitments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'category_id', 'recruitment_type', 'recruitment_state'], 'required'],
            [['business_id', 'category_id', 'recruitment_type', 'recruitment_number', 'recruitment_salary_min', 'recruitment_endtime', 'recruitment_creattime', 'recruitment_top', 'recruitment_top_price', 'recruitment_top_begintime', 'recruitment_top_endtime', 'recruitment_sys_state', 'recruitment_state'], 'integer'],
            [['recruitment_video', 'recruitment_text', 'recruitment_requriment'], 'string', 'max' => 100],
            [['recruitment_pic'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recruitment_id' => '招聘id',
            'business_id' => '商家id',
            'category_id' => '栏目id',
            'recruitment_type' => '类型：0招聘1培训2广告',
            'recruitment_video' => '视频路径',
            'recruitment_text' => '文字描述',
            'recruitment_pic' => '图片路径，可多张',
            'recruitment_number' => '招聘人数',
            'recruitment_salary_min' => '底薪',
            'recruitment_requriment' => '个性化要求',
            'recruitment_endtime' => '招聘结束时间',
            'recruitment_creattime' => '招聘创建时间',
            'recruitment_top' => '是否置顶:0无1是',
            'recruitment_top_price' => '置顶金额',
            'recruitment_top_begintime' => '置顶开始时间',
            'recruitment_top_endtime' => '置顶结束时间',
            'recruitment_sys_state' => '系统审核招聘状态：－1删除，0正常，1待审核，2审核未通过',
            'recruitment_state' => '商家自己控制招聘状态，－1删除，0正常，1关闭，2招满3已结束',
        ];
    }
}
