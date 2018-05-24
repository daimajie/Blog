<?php

namespace app\models\comment;

use app\components\Helper;
use app\components\View;
use Yii;
use app\models\content\Article;
use app\models\member\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\Pagination;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id ID
 * @property string $content 内容
 * @property int $user_id 用户id
 * @property int $article_id 文章id
 * @property int $reply 是否是回复
 * @property int $comment_id 评论id
 * @property int $created_at 创建时间
 *
 * @property Article $article
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['user_id', 'article_id', 'reply', 'comment_id', 'created_at'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    public function behaviors()
    {

        return [
            [
              'class' => BlameableBehavior::className(),
              'createdByAttribute' => 'user_id',
              'updatedByAttribute' => null,
            ],

            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'user_id' => '用户id',
            'article_id' => '文章id',
            'reply' => '是否是回复',
            'comment_id' => '评论id',
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
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->select(['id','username','photo','nickname']);
    }

    public function getReplys()
    {
        return $this->hasMany(self::className(), ['comment_id' => 'id'])
            ->orderBy(['created_at'=>SORT_DESC,'id'=>SORT_DESC]);
    }


    public static function getComments($curr=1, $limit=15, $article_id){
        if($limit > Yii::$app->params['pageSize'])
            $limit = Yii::$app->params['pageSize'];


        $query = self::find()
            ->where([
                'article_id' => $article_id,
                'reply' => 0
            ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count]);

        //设置页码
        $pagination->setPage($curr-1);
        $pagination->setPageSize($limit);

        $data = $query
            ->with('replys')
            ->with('replys.user')
            ->with('user')
            ->where(['article_id'=>$article_id])
            ->andWhere(['reply'=>0]) //0为评论1为回复
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['created_at'=>SORT_DESC,'id'=>SORT_DESC])
            ->asArray()
            ->all();

        $data = self::deepSet($data);

        return $data;
    }

    /**
     * 第归设置数据的头像 格式化时间
     */
    private static function deepSet($data){
        $data = Helper::photoInPlace($data);
        foreach($data as $k => &$v){
            $v['self'] = $v['user_id'] == Yii::$app->user->id;
            $v['created_at'] = View::timeFormat($v['created_at']);
            if(empty($v['replys']))continue;
            $v['replys'] = self::deepSet($v['replys']);
        }
        return $data;
    }




}
