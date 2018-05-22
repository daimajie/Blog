<?php

namespace app\models\notebook;

use Yii;
use app\models\member\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\Pagination;


/**
 * This is the model class for table "{{%notebook}}".
 *
 * @property int $id ID
 * @property string $content 内容
 * @property int $user_id 署名
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 *
 * @property User $user
 */
class Notebook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notebook}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
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
            'user_id' => '署名',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->select(['id','username','photo']);
    }

    /**
     * 获取日记列表
     * @params $curr int #当前请求页
     * @params $limit int #每页限制数目
     * @return array|null #日记几何
     */
    public static function getNotes($curr=1, $limit=15){
        $query = self::find();
        $count = $query->count();

        $pagination = new Pagination(['totalCount' => $count]);
        //配置当前页码
        $limit = ($limit > Yii::$app->params['pageSize']) ? Yii::$app->params['pageSize'] : $limit;
        $pagination->setPageSize($limit);
        $pagination->setPage($curr - 1);

        $data = $query
            ->alias('n')
            ->with('user')
            ->select(['n.content,from_unixtime(n.created_at) as created_at,n.user_id'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $data;
    }
}
