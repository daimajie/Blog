<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property int $id ID
 * @property string $name 标签名
 * @property int $topic_id 所属话题
 *
 * @property ArticleTag[] $articleTags
 * @property Topic $topic
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * #检测是否已达到标签个数上限 超过禁止再创建
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $max = Yii::$app->params['content.tag.max'];

            $topic_id = $this->topic_id;

            //获取当前话题下标签个数并判断是否已达上限
            $count = self::find()->where(['topic_id'=>$topic_id])->count();
            if($count > $max){
                $this->addError('name', '当前话题包含标签已达到上限。');
                return false;
            }

            //判断当前标签是否存在该标签
            $name = $this->name;
            $model = self::find()->where(['and',['topic_id'=>$topic_id], ['name'=>$name]])->limit(1)->one();
            if($model){
                $this->id = $model->id;
                $this->addError('name', '当前话题已经存在该标签。');
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','topic_id'], 'required'],
            [['topic_id'], 'integer'],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '标签名',
            'topic_id' => '所属话题',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])
            ->viaTable('{{%ArticleTag}}', ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }

    /**
     * 根据话题ID 获取当前所有的标签个数(可检测是否可删除该话题)
     * @param $id array|int #话题id
     * @return int #包含标签个数
     */
    public static function getTagsCountById($id){
        $query = self::find();

        if(is_array($id))
            $query->andWhere(['in', 'topic_id', $id]);

        elseif(is_int($id) && $id > 0)
            $query->andWhere(['topic_id'=>$id]);

        else{
            unset($query);
            return (int)false;
        }
        return $query->count();
    }

    /**
     * 提供话题id 获取所有标签数据 如果提供文章id则附加上与文章关联的标识
     * @param $topic_id int #话题id
     * @param $aid int #文章id
     * @return array #标签数据
     */
    /*public static function getTagsById($topic_id, $aid = 0){
        return self::find()
            ->where(['topic_id'=>$topic_id])
            ->asArray()
            ->all();
    }*/
    public static function getTagsById($topic_id, $aid = 0){
        $ret = self::find()
            ->where(['topic_id'=>$topic_id])
            ->asArray()
            ->all();

        //如果之提供话题id 返回该话题下所有标签
        if($aid <= 0)
            return $ret;

        $sel = ArticleTag::find()
            ->select(['tag_id'])
            ->where(['article_id'=>$aid])
            ->asArray()
            ->column();

        //添加标识 checked 标识
        foreach($ret as $k => &$v){
            if(in_array($v['id'], $sel)){
                $v['checked'] = 'checked';
            }
        }

        return $ret;

    }
}
