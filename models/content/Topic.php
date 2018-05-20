<?php

namespace app\models\content;
use yii\behaviors\TimestampBehavior;
use Yii;

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



}
