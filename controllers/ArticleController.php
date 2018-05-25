<?php
namespace app\controllers;

use app\components\View;
use app\models\collect\LikesCollect;
use app\models\comment\Comment;
use app\models\content\Article;
use Yii;
use yii\base\Exception;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;

class ArticleController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add-likes'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add-likes','del-likes','add-collect','del-collect'],/*每个方法内也要添加限制*/
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * 文章详情
     */
    public function actionIndex($article_id){
        //获取文章详情
        $article = Article::getAticleDetail($article_id);

        //获取文章上一页和下一页
        $prevAndNext = Article::PrevAndNext($article_id, $article['topic_id']);


        //获取当前用户是否收藏喜欢该文章
        $likes = $collect = 0;
        if(!Yii::$app->user->isGuest){
            $likes = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 0,
            ]);
            $collect = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 1,
            ]);
        }

        //当前用户模型
        $identity = Yii::$app->user->identity;

        //获取该文章评论数目
        $commentCount = Comment::find()->where([
            'article_id' => $article->id,
            'reply' => 0,
        ])->count();




        //累加阅读数
        //$article->updateCounters(['visited' => 1]);

        return $this->render('index',[
            'article' => $article,
            'prevAndNext' => $prevAndNext,
            'likes' => $likes,
            'collect' =>$collect,
            'identity' => $identity,
            'commentCount' => $commentCount,
            'limit' => Yii::$app->params['pageSize'],
        ]);
    }
    
    
    /**
     * 点赞
     */
    public function actionAddLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请先登录，在进行点赞操作。');


            //获取模型
            $article_id = (int)Yii::$app->request->post('article_id');
            $article = self::getModel($article_id);

            //检测是否存在
            $isExist = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 0,
            ]);
            if($isExist)
                throw new Exception('已经点赞此文章，请不要重复操作。');


            //写入关联数据
            $model = new LikesCollect();
            $model->article_id = $article->id;
            $model->user_id = Yii::$app->user->id;
            $model->type = 0;//0为点赞1为收藏

            if(!$model->save(false) !== false)
                throw new Exception('写入数据失败，请重试。');

            //累计文章喜欢数
            $article->updateCounters(['likes' => 1]);

            return ['errno'=>0, 'message'=>'点赞成功。'];

        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    /**
     * 取消点赞
     */
    public function actionDelLikes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请先登录，在进行此操作。');

            //获取模型
            $article_id = (int)Yii::$app->request->post('article_id');
            $article = self::getModel($article_id);



            //写入关联数据
            $model = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 0,
            ]);


            if($model->delete() === false)
                throw new Exception('写入数据失败，请重试。');

            //累计文章喜欢数
            $article->updateCounters(['likes' => -1]);

            return ['errno'=>0, 'message'=>'取消点赞成功。'];

        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    /**
     * 收藏
     */
    public function actionAddCollect(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请先登录，在进行收藏操作。');

            //获取模型
            $article_id = (int)Yii::$app->request->post('article_id');
            $article = self::getModel($article_id);

            //检测是否存在
            $isExist = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 1, //1为收藏0为喜欢
            ]);
            if($isExist)
                throw new Exception('已经收藏此文章，请不要重复操作。');


            //写入关联数据
            $model = new LikesCollect();
            $model->article_id = $article->id;
            $model->user_id = Yii::$app->user->id;
            $model->type = 1;//0为点赞1为收藏

            if(!$model->save(false) !== false)
                throw new Exception('写入数据失败，请重试。');

            //累计文章喜欢数
            $article->updateCounters(['collect' => 1]);

            return ['errno'=>0, 'message'=>'收藏成功。'];


        }catch (Exception $e){
            return ['errno' => 1, 'message' => $e->getMessage()];
        }
    }

    /**
     * 取消收藏
     */
    public function actionDelCollect(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请先登录，在进行此操作。');

            //获取模型
            $article_id = (int)Yii::$app->request->post('article_id');
            $article = self::getModel($article_id);


            //写入关联数据
            $model = LikesCollect::findOne([
                'user_id' => Yii::$app->user->id,
                'article_id' => $article->id,
                'type' => 1, //1为收藏0为喜欢
            ]);

            if($model->delete() === false)
                throw new Exception('写入数据失败，请重试。');

            //累计文章喜欢数
            $article->updateCounters(['collect' => -1]);

            return ['errno'=>0, 'message'=>'取消收藏成功。'];


        }catch (Exception $e){
            return ['errno' => 1, 'message' => $e->getMessage()];
        }
    }


    /**
     * 提交评论
     */
    public function actionComment(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请登录后进行评论操作。');

            //接收数据
            $content = Yii::$app->request->post('content');
            $article_id = (int)Yii::$app->request->post('article_id');

            $comment_id = (int)Yii::$app->request->post('comment_id');


            //写入数据
            $comment = new Comment();
            $comment->content = $content;
            $comment->article_id = $article_id;
            if($comment_id){
                //恢复
                $comment->reply = 1;
                $comment->comment_id = $comment_id;
            }else{
                //评论
                $comment->reply = 0; //0为评论1为回复
            }


            if($comment->save() === false)
                throw new Exception('写入评论失败，请重试。');

            //评论数累加
            Article::updateAllCounters(['comment'=>1],['id'=>$comment->article_id]);

            return [
                'errno'=>0,
                'message'=>'写入评论成功。',
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

    /**
     * 删除评论
     */
    public function actionDelComment(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //是否登录
            if(Yii::$app->user->isGuest)
                throw new Exception('请登录后进行评论操作。');

            //获取请求参数
            $article_id = (int)Yii::$app->request->post('article_id');
            $id = (int)Yii::$app->request->post('id');
            $reply = Yii::$app->request->post('reply');


            //获取评论模型
            $comment = Comment::find()->where([
                'id' => $id,
                'article_id' => $article_id,
                'user_id' => Yii::$app->user->id,
                'reply' => $reply,
            ])->one();

            if(!$comment)
                throw new NotFoundHttpException('没有相关数据。');

            //删除该条评论的所有回复
            $num = 0;
            if($reply == 0){
                $num = Comment::deleteAll([
                    'reply' => 1, //回复
                    'comment_id' => $comment->id,
                    'article_id' => $comment->article_id,
                ]);
            }



            //删除评论
            if($comment->delete() === false)
                throw new Exception('删除评论失败，请重试。');

            @Article::updateAllCounters(['comment' => -(1 + (int)$num)], ['id'=>$comment->article_id]);

            //返回结果
            return ['errno'=>0, 'message'=>'删除评论成功。'];
        }catch (Exception $e){
            return ['errno' => 1, 'message'=>$e->getMessage(), 'data'=>[]];
        }
    }

    /**
     * 获取评论数据
     */
    public function actionGetComments(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            //获取分页数据
            $curr = (int)Yii::$app->request->post('curr');
            $limit = (int)Yii::$app->request->post('limit');
            $article_id = (int)Yii::$app->request->post('article_id');

            //获取评论列表
            $comments = Comment::getComments($curr, $limit, $article_id);
            if(!$comments)
                throw new NotFoundHttpException('没有数据。');

            //返回数据
            return [
                'errno' => 0,
                'data' => $comments,
            ];


        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage(),'data'=>[]];
        }
    }



    /**
     * 获取模型
     */
    private static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = Article::findOne($id);
        if(!$model)
            throw new NotFoundHttpException('没有相关数据。');
        return $model;
    }




}