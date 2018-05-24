<?php
namespace app\controllers;
use app\models\comment\Comment;
use Yii;
use yii\base\Exception;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use app\components\View;

class CommentController extends BaseController
{



    /**
     * 给评论添加喜欢
     */
    public function actionAddLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否的登录状态
            if(Yii::$app->user->isGuest)
                throw new Exception('请登录后在作此操作。');

            //获取数据
            $article_id = (int)Yii::$app->request->post('article_id');
            $id = (int)Yii::$app->request->post('id');

            //点赞
            $comment = Comment::findOne([
                'id'=>$id,
                'article_id'=>$article_id,
            ]);
            $comment->updateCounters(['likes'=>1]);

            //返回信息
            return ['errno'=>0, 'message'=>'点赞成功。'];


        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage(), 'data'=>[]];
        }
    }

    /**
     * 取消喜欢
     */
    public function actionDelLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否的登录状态
            if(Yii::$app->user->isGuest)
                throw new Exception('请登录后在作此操作。');

            //获取数据
            $article_id = (int)Yii::$app->request->post('article_id');
            $id = (int)Yii::$app->request->post('id');

            //点赞
            $comment = Comment::findOne([
                'id'=>$id,
                'article_id'=>$article_id,
            ]);
            @$comment->updateCounters(['likes'=>-1]);

            //返回信息
            return ['errno'=>0, 'message'=>'取消点赞成功。'];


        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage(), 'data'=>[]];
        }
    }

    /**
     * 提交回复
     */
    public function actionAddReplys(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请先登录，再作此操作。');

            //获取参数
            $content = Yii::$app->request->post('content');
            $article_id = (int)Yii::$app->request->post('article_id');
            $comment_id = (int)Yii::$app->request->post('comment_id');

            //写入数据
            $comment = new Comment();
            $comment->content = trim($content);
            $comment->article_id = $article_id;
            $comment->reply = 1;
            $comment->comment_id = $comment_id;
            if($comment->save() === false)
                throw new Exception('写入回复失败，请重试。');

            //返回结果
            return [
                'errno'=>0,
                'message'=>'写入回复成功。',
                'data' => [
                    'reply' => $comment->reply,
                    'photo' => Yii::$app->user->identity->pic,
                    'username' => Yii::$app->user->identity->username,
                    'created_at' =>View::timeFormat($comment->created_at),
                    'content' => $comment->content,
                    'id' => $comment->id,
                ],
            ];
        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

}