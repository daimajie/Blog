<?php
namespace app\modules\admin\modules\comment\controllers;
use app\modules\admin\controllers\BaseController;
use app\modules\admin\modules\content\models\Article;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\admin\modules\comment\models\Comment;

class CommentController extends BaseController
{
    /**
     * 评论列表
     */
    public function actionComment(){

        return $this->render('comment');
    }

    /**
     * 获取表格数据
     */
    public function actionGetComments($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $query = Comment::find()->where(['reply'=>0]);//排除回复数据
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);

            //配置当前页码
            list($page, $limit) = [(int)$page, (int)$limit];
            if ($page < 0 || $limit < 0)
                throw new BadRequestHttpException('请求参数错误。');
            //设置页码
            $pagination->setPageSize($limit);
            $pagination->setPage($page - 1);

            //获取数据
            $data = $query
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy(['created_at'=>SORT_ASC, 'id' => SORT_DESC])
                ->asArray()
                ->all();
            //VarDumper::dump($data,10,1);die;
            if (empty($data))
                throw new NotFoundHttpException('没有相关数据。');

            return [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $data
            ];



        }catch (Exception $e){
            return ['code'=>1,'msg'=>$e->getMessage(),'data'=>[]];
        }
    }

    /**
     * 评论删除
     */
    public function actionDelComment(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //获取参数
            $id = (int)Yii::$app->request->post('id');

            $model = Comment::findOne($id);
            if(!$model)
                throw new NotFoundHttpException('没有相关数据。');

            //获取文章模型
            $article = Article::findOne($model->article_id);

            //减去计数 删除所属回复
            $count = Comment::deleteAll(['comment_id'=>$model->id]);

            if($model->delete() === false)
                throw new Exception('删除失败请重试。');


            @$article->updateCounters(['comment'=>-(1+$count)]);

            return ['errno'=>0, 'message'=>'删除成功。'];

        }catch (Exception $e){
            return ['errno'=>1,'message'=>$e->getMessage()];
        }
    }

    /**
     * 回复列表
     */
    public function actionReply(){

        return $this->render('reply');
    }

    /**
     * 获取回复列表
     */
    public function actionGetReplys($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $query = Comment::find()->where(['reply'=>1]);//回复数据
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);

            //配置当前页码
            list($page, $limit) = [(int)$page, (int)$limit];
            if ($page < 0 || $limit < 0)
                throw new BadRequestHttpException('请求参数错误。');
            //设置页码
            $pagination->setPageSize($limit);
            $pagination->setPage($page - 1);

            //获取数据
            $data = $query
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy(['created_at'=>SORT_ASC, 'id' => SORT_DESC])
                ->asArray()
                ->all();

            if (empty($data))
                throw new NotFoundHttpException('没有相关数据。');

            return [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $data
            ];



        }catch (Exception $e){
            return ['code'=>1,'msg'=>$e->getMessage(),'data'=>[]];
        }
    }

    /**
     * 删除回复
     */
    public function actionDelReply(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //获取参数
            $id = (int)Yii::$app->request->post('id');

            $model = Comment::findOne($id);
            if(!$model)
                throw new NotFoundHttpException('没有相关数据。');

            //获取文章模型
            $article = Article::findOne($model->article_id);


            if($model->delete() === false)
                throw new Exception('删除失败请重试。');


            @$article->updateCounters(['comment'=>-1]);

            return ['errno'=>0, 'message'=>'删除成功。'];

        }catch (Exception $e){
            return ['errno'=>1,'message'=>$e->getMessage()];
        }
    }

}