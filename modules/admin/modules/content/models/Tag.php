<?php
namespace app\modules\admin\modules\content\models;
use app\models\content\ArticleTag;
use app\models\content\Tag as TagModel;
use yii\db\Exception;

class Tag extends TagModel
{
    /**
     * 标签删除
     */
    public function delTagAndRelated(){
        $transaction = self::getDb()->beginTransaction();
        try {
            //删除标签关联数据
            if(ArticleTag::deleteAll(['tag_id'=>$this->id]) === false){
                throw new Exception('删除标签及关联数据失败。');
            }
            //删除标签
            if($this->delete() === false){
                throw new Exception('删除标签失败。');
            }

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}