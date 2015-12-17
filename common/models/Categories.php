<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rc_categories".
 *
 * @property integer $category_id
 * @property integer $category_parent_id
 * @property string $category_name
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rc_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_parent_id', 'category_name'], 'required'],
            [['category_parent_id'], 'integer'],
            [['category_name'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => '栏目id',
            'category_parent_id' => '栏目父id，顶级父id为0',
            'category_name' => '栏目名',
        ];
    }
}
