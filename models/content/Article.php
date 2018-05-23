<?php

namespace app\models\content;

use app\components\Helper;
use app\models\member\User;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

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
            ->select(['id','username','photo','author','total']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%article_tag}}', ['article_id' => 'id']);
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


    /**
     * 获取指定文章详情
     * @param $article_id int #文章id
     * @return array #文章详情
     */
    public static function getAticleDetail($article_id){
        $id = (int)$article_id;
        if($id <= 0) throw new BadRequestHttpException('请求参数错误。');

        $article = self::find()->with('user')->with('topic')->with('content')->with('tags')
            ->select(['id','title','type','visited','comment','likes','created_at','user_id','topic_id','content_id'])
            ->where(['id'=>$article_id])
            ->andWhere(['and', 'draft != 1', 'recycle != 1'])
            //->asArray()
            ->one();
        if(!$article)
            throw new NotFoundHttpException('没有找到相关数据');

        //设置头像
        $article = Helper::photoInPlace([$article])[0];

        //设置类型
        $type = [1=>'原创',2=>'翻译',3=>'转载'];
        $article['type'] = $type[$article['type']];

        //设置角色
        $author = ['普通用户','签约作者','管理'];
        $article['user']['author'] = $author[$article['user']['author']];

        //返回数据
        return $article;
    }

    /**
     * 获取上一篇和下一篇
     * @param int $article_id #当前文章id
     * @param int $subject_id #当前专题
     * @return array
     */
    public static function prevAndNext($article_id, $topic_id){
        $previous = self::find()
            ->select(['id', 'title'])
            ->andFilterWhere(['<', 'id', $article_id])
            ->andFilterWhere(['topic_id'=>$topic_id])
            ->andFilterWhere(['!=', 'recycle', 1])
            ->andFilterWhere(['!=', 'draft', 1])
            ->orderBy(['id'=>SORT_DESC])
            ->limit(1)
            ->one();
        $next = self::find()
            ->select(['id', 'title'])
            ->andFilterWhere(['>', 'id', $article_id])
            ->andFilterWhere(['topic_id'=>$topic_id])
            ->andFilterWhere(['!=', 'recycle', 1])
            ->andFilterWhere(['!=', 'draft', 1])
            ->orderBy(['id'=>SORT_ASC])
            ->limit(1)
            ->one();

        //拼接url
        $prev_article = [
            'url' => !is_null($previous) ? Url::current(['article_id'=>$previous->id]):'javascript:void(0);',
            'title' => !is_null($previous)?$previous->title:'已经是第一篇了',
        ];
        $next_article = [
            'url' => !is_null($next)?Url::current(['article_id'=>$next->id]):'javascript:void(0);',
            'title' => !is_null($next)?$next->title:'已经是最后一篇了',
        ];
        return [
            'prev' => $prev_article,
            'next' => $next_article
        ];
    }









}
