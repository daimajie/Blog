<?php

namespace app\models\content;

use app\models\member\User;

/**
 * This is the model class for table "{{%article}}".
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
   /* public function rules()
    {
        return [
            [['type', 'draft', 'recycle', 'visited', 'comment', 'likes', 'topic_id', 'content_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['brief'], 'string', 'max' => 512],
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '文章标题',
            'brief' => '文章简介',
            'type' => '文章类型',
            'draft' => '草稿',
            'topic_id' => '所属话题ID',
            'content_id' => '内容ID',

            'pri_content' => '文章内容',
            'pri_tags' => '新建标签',
            'topic' => '话题',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['id' => 'content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id'])
            ->select(['id','name']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->select(['id','username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'article_id'])
            ->viaTable('{{%article_tag}}', ['tag_id' => 'id']);
    }

    /**
     * 获取当前文章的相关数据（标签名，所选标签， 文章内容）
     */
    public function getRelatedData(){
        $this->topic = Topic::find()->select(['name'])->where(['id'=>$this->topic_id])->scalar();
        //获取选中的标签数据
        $this->tags = ArticleTag::find()
            ->select(['tag_id'])
            ->where(['article_id'=>$this->id])
            ->asArray()
            ->column();
        //获取内容
        $this->pri_content = Content::find()
            ->select(['content'])
            ->where(['id'=>$this->content_id])
            ->asArray()
            ->scalar();
    }
}
