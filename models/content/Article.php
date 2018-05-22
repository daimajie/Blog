<?php

namespace app\models\content;

use app\models\member\User;
use yii\data\Pagination;

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
            ->select(['id','username','photo']);
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

    /**
     * #获取文章列表（后台数据列表）
     */
    public static function getArticles($page, $limit, $type='index'){
        $query = self::find();

        //获取文章类型(回收站，草稿箱)
        if($type === 'recycle-box'){
            //获取回收站数据
            $query->andWhere('recycle=1');
        }elseif($type === 'draft-box'){
            //获取草稿箱数据
            $query->andWhere(['and', 'draft=1', 'recycle!=1']);
        }else{
            //获取排除回收站及草稿箱的数据
            $query->andWhere(['and','recycle != 1','draft != 1']);
        }


        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        //配置当前页码
        $pagination->setPageSize($limit);
        $pagination->setPage($page - 1);

        //获取数据
        $data = $query->with('user')->with('topic')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at' => SORT_DESC,'id' => SORT_DESC])
            ->asArray()
            ->all();
        $type = [1=>'原创','翻译','转载'];
        foreach ($data as $k => &$v){
            $v['type'] = $type[$v['type']];
            $v['author'] = $v['user']['username'];
            $v['topicName'] = $v['topic']['name'];
        }

        return [
            'data' => $data,
            'count' => $count,
        ];


    }

    /**
     * #获取文章列表 （前台数据列表）
     */
    public static function getArticlesAll($page, $limit){
        //排除草稿箱 和 回收站的文章
        $query = self::find()->andWhere(['and','recycle != 1','draft != 1']);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        //配置当前页码
        $pagination->setPageSize($limit);
        $pagination->setPage($page - 1);

        //获取数据
        $data = $query
            ->with('user')
            ->with('topic')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at' => SORT_DESC,'id' => SORT_DESC])
            ->asArray()
            ->all();

        return $data;

    }









}
