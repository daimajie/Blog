<?php

namespace app\models\content;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use app\components\View;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%topic}}".
 *
 * @property int $id ID
 * @property string $name 话题名
 * @property string $desc 简述
 * @property int $category_id 所属分类
 * @property int $created_at 创建时间
 *
 * @property Category $category
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic}}';
    }

    /**
     * 行为
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','category_id'], 'required'],
            [['name'], 'string', 'max' => 15],
            [['desc'], 'string', 'max' => 255],
            [['category_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '话题名',
            'desc' => '简述',
            'category_id' => '所属分类',
            'created_at' => '创建时间',
            'count' => '收录文章'
        ];
    }

    /**
     * 关联
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->select(['id', 'name']);;
    }

    public function getTags(){

        return $this->hasMany(Tag::className(), ['topic_id' => 'id'])->select(['topic_id', 'name', 'id']);
    }

    /**
     * 获取指定分类下的所有话题个数(可检测是否可以删除分类)
     * @params $id array|int #分类id
     * @return int #包含话题个数
     */
    public static function getTopicsCountById($id){
        $query =self::find();

        if(is_array($id))
            $query->andWhere(['in', 'category_id', $id]);

        elseif(is_int($id) && $id > 0)
            $query->andWhere(['category_id'=>$id]);

        else{
            //关闭资源
            unset($query);
            return (int)false;
        }
        return $query->count();
    }

    /**
     * 获取指定话题下的所有文章 可同时指定匹配某个标签
     * @params $topic_id int #话题id
     * @params $tag_id int #标签id
     * @params $curr int #当前页码
     * @params $limit int #每页限数
     * @return array | int #如果只指定话题标签返回匹配的文章数目，同时指定页码和限数则返回匹配的文章列表
     */
    public static function getArticlesByTopicAndTag($topic_id, $tag_id=null,$curr=null, $limit=null){
        //获取查询对象
        $query = self::getQueryBuild($topic_id, $tag_id);
        $count = $query->count();

        //如果未指定页码说明之获取匹配文章数目
        if(empty($curr) || empty($limit))
            return $count;

        //分页 否则获取文章列表
        $pagiantion = new Pagination(['totalCount'=>$count]);

        //配置页码
        $pagiantion->setPage($curr-1);
        $pagiantion->setPageSize($limit);

        //获取数据
        $articles = $query->with('user')->with('topic')
            ->select(['article.*'])
            ->offset($pagiantion->offset)
            ->limit($pagiantion->limit)
            ->orderBy(['created_at'=>SORT_DESC, 'id'=>SORT_DESC])
            ->asArray()
            ->all();
        foreach($articles as $key => &$val){
            $val['created_at'] = View::timeFormat($val['created_at']);
            $val['article_url'] = Url::to(['article/index','article_id'=>$val['id']]);
            //$val['topic']['topic_url'] = Url::to(['topic/index','topic_id' => $val['topic']['id']]);
        }

        return $articles;

    }

    /**
     * 获取查询生成器
     */
    private static function getQueryBuild($topic_id, $tag_id){
        $topic_id = (int)$topic_id;
        $tag_id = (int)$tag_id;
        if($topic_id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $query = Article::find()
            ->where('recycle != 1')
            ->andWhere('draft != 1')
            ->andWhere(['topic_id'=>$topic_id]);

        if(!empty($tag_id) && $tag_id > 0){
            $query//->select(['{{%article}}.*','{{%article_tag}}.*'])
                ->leftJoin('{{%article_tag}}','{{%article_tag}}.article_id={{%article}}.id')
                ->andWhere(['tag_id'=>$tag_id]);
        }
        return $query;
    }









}
