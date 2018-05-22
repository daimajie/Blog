<?php
namespace app\modules\admin\modules\content\models;
use app\models\content\Article as ArticleModel;
use app\models\content\ArticleTag;
use app\models\content\Content;
use app\models\content\Tag;
use app\models\content\Topic;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use Yii;


class Article extends ArticleModel
{
    public $pri_content;    //新建内容
    public $pri_tags;       //新建标签
    public $topic;          //搜索专题名称
    public $tags;           //当前专题下所有的标签

    public function rules()
    {
        return [
            [['topic_id', 'topic', 'title', 'brief', 'type', 'draft', 'pri_content'], 'required'],

            [['topic_id', 'type', 'draft'], 'integer'],
            ['type', 'in', 'range' => [1, 2, 3], 'message' => '请正确指定文章类型'],
            ['draft', 'in', 'range' => [0, 1], 'message' => '请正确指定文章发布状态'],

            [['title', 'brief','pri_tags'], 'filter', 'filter'=>'trim'],
            [['title'], 'string', 'max' => 255],
            [['brief'], 'string', 'max' => 512],
            [['pri_tags'], 'string', 'max' => 64],

            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['topic'], 'checkTopic'],

            [['tags'], 'safe'],
        ];
    }
    public function checkTopic($attribute, $params){
        if($this->hasErrors('topic_id')){
            $this->addError($attribute, '请搜索并指定一个有效话题。');
        }
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ],
        ];
    }

    /**
     * #保存文章
     * @return bool
     * @throws Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function store(){
        //验证
        if(!$this->validate())
            return false;

        //保存 文章内容 标签 文章 及文章与标签关联数据
        $transaction = self::getDb()->beginTransaction();
        try{
            //写入内容
            if($this->pri_content){
                $content = new Content();
                $content->content = $this->pri_content;
                if(!$content->save()){
                    throw new Exception('内容保存失败，请重试。');
                }
                $this->content_id = $content->id;
            }
            //写入文章
            if(!$this->save(false)){
                throw new Exception('文章保存失败，请重试。');
            }

            //写入标签
            $tag_ids = [];
            if(!empty($this->pri_tags) && $this->topic_id > 0){
                //保存标签
                $tags = explode(',', str_replace('，', ',', $this->pri_tags));

                foreach($tags as $k => $v){
                    $tag = new Tag();
                    $tag->name = $v;
                    $tag->topic_id = $this->topic_id;
                    if(!$tag->save() && empty($tag->id)){
                        throw new Exception($tag->getErrors('name')[0]);
                    }
                    $tag_ids[] = $tag->id; //保存标签id
                }
            }

            //如果有选择标签 就合并新建标签id
            if(!empty($this->tags)){
                $tag_ids = array_unique(array_merge($tag_ids, $this->tags));
            }


            //写入文章标签关联
            if(!empty($tag_ids)){
                foreach ($tag_ids as $key => $val){
                    $atModel = new ArticleTag();
                    $atModel->article_id = $this->id;
                    $atModel->tag_id = $val;
                    if(!$atModel->save()){
                        throw new Exception('文章标签关联数据保存失败，请重试。');
                    }
                }
            }

            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            throw $e;
        }catch(\Throwable $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 编辑文章
     */
    public function renew(){
        //验证
        if(!$this->validate())
            return false;

        //保存 文章内容 标签 文章 及文章与标签关联数据
        $transaction = self::getDb()->beginTransaction();
        try{
            //写入内容
            if($this->pri_content){
                $content = Content::findOne(['id'=>$this->content_id]);
                $content->content = $this->pri_content;
                if(!$content->save()){
                    throw new Exception('内容保存失败，请重试。');
                }
            }
            //写入文章
            if(!$this->save(false)){
                throw new Exception('文章保存失败，请重试。');
            }

            //写入标签
            $tag_ids = [];
            if(!empty($this->pri_tags) && $this->topic_id > 0){
                //保存标签
                $tags = explode(',', str_replace('，', ',', $this->pri_tags));

                foreach($tags as $k => $v){
                    $tag = new Tag();
                    $tag->name = $v;
                    $tag->topic_id = $this->topic_id;
                    if(!$tag->save() && empty($tag->id)){
                        throw new Exception($tag->getErrors('name')[0]);
                    }
                    $tag_ids[] = $tag->id; //保存标签id
                }
            }

            //如果有选择标签 就合并新建标签id
            if(!empty($this->tags)){
                $tag_ids = array_unique(array_merge($tag_ids, $this->tags));
            }

            //清空文章标签关联数据
            if(ArticleTag::deleteAll(['article_id'=>$this->id]) === false){
                throw new Exception('文章标签关联数据编辑失败，请重试。');
            }

            //写入文章标签关联
            if(!empty($tag_ids)){

                foreach ($tag_ids as $key => $val){
                    $atModel = new ArticleTag();
                    $atModel->article_id = $this->id;
                    $atModel->tag_id = $val;
                    if(!$atModel->save()){
                        throw new Exception('文章标签关联数据保存失败，请重试。');
                    }
                }
            }

            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            throw $e;
        }catch(\Throwable $e){
            $transaction->rollBack();
            throw $e;
        }
    }




    /**
     * 删除文章关联数据 文章内容，文章标签关联数据
     */
    public function deleteArticle(){
        $transaction = $transaction = self::getDb()->beginTransaction();;
        try{
            //删除标签关联数据
            if(ArticleTag::deleteAll(['article_id'=>$this->id]) === false)
                throw new Exception('删除标签关联数据失败。');

            //删除文章
            if($this->delete() === false){
                throw new Exception('删除文章失败。');
            }

            //删除内容
            if(Content::deleteAll(['id'=>$this->id]) === false)
                throw new Exception('删除文章内容失败。');

            $transaction->commit();
        }catch (Exception $e){
            $transaction->rollBack();
            throw $e;
        }catch(\Throwable $e){
            $transaction->rollBack();
            throw $e;
        }


    }



}