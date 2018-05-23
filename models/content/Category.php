<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use app\models\content\Topic;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id ID
 * @property string $name 分类名
 * @property string $desc 简述
 */
class Category extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
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
            [['name', 'desc'], 'filter', 'filter'=>'trim'],
            [['name'], 'required', 'message' => '必须填写用分类名.'],
            [['name'], 'string', 'max' => 15],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'desc' => '简述',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::className(), ['category_id' => 'id'])
            ->select(['id','category_id','name','created_at','desc'])
            ->orderBy(['created_at'=>SORT_DESC])
            ->limit(7);
    }


    public static function getCategoryAll($curr, $limit){
        $query = self::find();
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);
        //设置页码
        $pagination->setPage($curr - 1);
        $pagination->setPageSize($limit);

        $category = $query->with('topics')
            ->select(['id','name','desc','from_unixtime(created_at) as created_at'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['id'=>SORT_DESC])
            ->asArray()
            ->all();
        //添加url
        $category = self::addUrl($category);

        return $category;
    }

    /**
     * 给分类列表添加url链接 可以点击跳至指定分类页 或是 话题页
     * 因为前台请求的数据不能动态生成
     */
    private static function addUrl($data){
        foreach ($data as $key => &$val){
            $val['url'] = Url::to(['category/detail','category_id'=>$val['id']]);
            foreach($val['topics'] as $k => &$v){
                $v['url'] = Url::to(['topic/index','topic_id'=>$v['id']]);
            }
        }
        return $data;
    }


}
