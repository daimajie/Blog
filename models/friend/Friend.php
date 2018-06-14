<?php

namespace app\models\friend;

use Yii;

/**
 * This is the model class for table "{{%friend}}".
 *
 * @property int $id ID
 * @property string $name 站点名称
 * @property string $url 站点地址
 * @property int $sort 排序
 */
class Friend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%friend}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','url'], 'required'],
            [['sort'], 'integer'],
            [['sort'], 'default', 'value'=>50],
            [['name'], 'string', 'max' => 32],
            [['name'], 'unique'],
            [['url'], 'string', 'max' => 128],
            [['url'], 'url', 'defaultScheme' => 'http'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '站点名称',
            'url' => '站点地址',
            'sort' => '排序',
        ];
    }
}
