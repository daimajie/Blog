<?php
namespace app\controllers;
use app\components\Helper;
use app\models\content\Tag;
use app\models\content\Topic;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\MethodNotAllowedHttpException;

class TopicController extends BaseController
{
    public $layout = 'layout-full';

    public function actionIndex($topic_id, $tag_id=null){

        //获取匹配指定话题的文章数目
        $count = Topic::getArticlesByTopicAndTag($topic_id, $tag_id);

        //获取话题标签
        $tags = Tag::find()->where(['topic_id'=>$topic_id])->asArray()->all();

        return $this->render('index',[
            'tags' => $tags,
            'count' => $count,
            'limit' => Yii::$app->params['pageSize'],
        ]);
    }

    /**
     * 获取文章列表
     */
    public function actionGetArticles(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //请求方式检测
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //请求参数获取
            $curr = (int)Yii::$app->request->post('curr');
            $limit =  (int)Yii::$app->request->post('limit');
            $topic_id = (int)Yii::$app->request->post('topic_id');
            $tag_id = (int)Yii::$app->request->post('tag_id');


            //获取文章列表
            $data = Topic::getArticlesByTopicAndTag($topic_id, $tag_id, $curr, $limit);
            if(!$data)
                throw new NotFoundHttpException('没有数据。');

            //头像设置
            $data = Helper::photoInPlace($data);

            //返回数据
            return ['errno'=>0, 'data'=>$data];

        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno' => 1,'data'=>[], 'message'=>$e->getMessage()];
        }
    }

}