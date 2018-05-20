<?php

namespace app\models\setting;

use Yii;

/**
 * This is the model class for table "{{%metas}}".
 *
 * @property int $id ID
 * @property string $sitename 站点名称
 * @property string $keywords 关键字
 * @property string $description 站点描述
 * @property string $aboutme 关于我
 */
class Metas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%metas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sitename'], 'string', 'max' => 32],
            [['keywords'], 'string', 'max' => 225],
            [['description', 'aboutme'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sitename' => '站点名称',
            'keywords' => '关键字',
            'description' => '站点描述',
            'aboutme' => '关于我',
        ];
    }
}
