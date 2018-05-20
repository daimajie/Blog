<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "{{%content}}".
 *
 * @property int $id ID
 * @property string $content 文章内容
 *
 * @property Article[] $articles
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '文章内容',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['content_id' => 'id']);
    }
}
