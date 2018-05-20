<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property int $tag_id 标签
 * @property int $article_id 文章
 *
 * @property Article $article
 * @property Tag $tag
 */
class ArticleTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'article_id'], 'integer'],
            [['tag_id', 'article_id'], 'required'],
//            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
//            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => '标签',
            'article_id' => '文章',
        ];
    }

}
