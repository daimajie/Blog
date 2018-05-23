<?php

namespace app\models\collect;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%likes_collect}}".
 *
 * @property int $id ID
 * @property int $user_id 用户id
 * @property int $article_id 文章id
 * @property int $type 0为喜欢点赞,1为收藏
 * @property int $created_at 创建时间
 *
 * @property Article $article
 * @property User $user
 */
class LikesCollect extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%likes_collect}}';
    }

    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'article_id', 'type', 'created_at'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'article_id' => '文章id',
            'type' => '0为喜欢点赞,1为收藏',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
