<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use app\models\content\Topic;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id ID
 * @property string $name 分类名
 * @property string $desc 简述
 */
class Category extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'desc'], 'filter', 'filter'=>'trim'],
            [['name'], 'required', 'message' => '必须填写用分类名.'],
            [['name'], 'string', 'max' => 15],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'desc' => '简述',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::className(), ['category_id' => 'id']);
    }


}
