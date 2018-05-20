<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Tag;
use app\modules\admin\modules\content\models\Article;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\MethodNotAllowedHttpException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;


class ArticleController extends BaseController
{
    //文章列表
    public function actionIndex(){
        
        return $this->render('index');
    }

    //文章添加
    public function actionCreate(){
        $model = new Article();

        if(Yii::$app->request->isPost){
            try{
                if($model->load(Yii::$app->request->post()) && $model->store()){
                    Yii::$app->session->setFlash('success', '创建文章成功。');
                    return $this->redirect(['index']);
                }
            }catch (Exception $e){
                Yii::$app->session->setFlash('fail', $e->getMessage());
            }catch (\Throwable $e){
                Yii::$app->session->setFlash('fail', $e->getMessage());
            }


            //获取话题所有标签
            if($model->topic_id){
                $tags = Tag::getTagsById($model->topic_id);
            }

        }

        return $this->render('create',[
            'model' => $model,
            'tags' => !empty($tags) ? $tags : null
        ]);
    }

    //文章修改
    public function actionUpdate($id){
        $model = self::getModel($id);

        //接收post提交
        if(Yii::$app->request->isPost){
            try{
                if($model->load(Yii::$app->request->post()) && $model->renew()){
                    Yii::$app->session->setFlash('success', '编辑文章成功。');
                    return $this->redirect(['index']);
                }
            }catch (Exception $e){
                Yii::$app->session->setFlash('fail', $e->getMessage());
            }catch (\Throwable $e){
                Yii::$app->session->setFlash('fail', $e->getMessage());
            }
        }

        //获取话题名
        $model->getRelatedData();

        return $this->render('create',[
            'model' => $model,
            'tags' => Tag::getTagsById($model->topic_id),
        ]);
    }

    //文章删除
    public function actionDelete($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $model = self::getModel($id);

            //删除文章及关联数据
            $model->deleteArticle();

            return ['errno'=>0, 'message'=>'文章删除成功。'];
        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }catch (\Throwable $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];

        }
    }

    //文章放入回收站
    public function actionRecycle(){

        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //获取文章id
            $aid = (int)Yii::$app->request->post('id');
            if($aid <= 0)
                throw new BadRequestHttpException('请求参数错误。');

            //获取模型、
            $model = self::getModel(24);
            $model->recycle = 1;
            if($model->save(false) === false){
                throw new Exception('放入回收站失败，清重试。');
            }

            return ['errno'=>0, 'message'=>'放入回收站成功。'];

        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }
    
    //恢复文章
    public function actionRestore(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许');

            $id = (int)Yii::$app->request->post('id');
            $model = self::getModel($id);
            $model->recycle = 0;
            if(!$model->save(false))
                throw new Exception('恢复文章失败，请重试。');

            return ['errno'=>0, 'message'=>'恢复文章成功。'];
        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    //表单数据
    public function actionGetTableData($page=1, $limit=10, $type='index'){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许。');

            //检测参数
            list($page, $limit) = [(int)$page, (int)$limit];
            if ($page < 0 || $limit < 0)
                throw new BadRequestHttpException('请求参数错误。');

            //获取文章数据
            $data = Article::getArticles($page, $limit, $type);

            if (empty($data['data']))
                throw new NotFoundHttpException('没有相关数据。');

            //VarDumper::dump($data,10,1);die;
            return [
                'code' => 0,
                'msg' => '',
                'count' => $data['count'],
                'data' => $data['data']
            ];


        }catch (MethodNotAllowedHttpException $e){
            //不被允许的请求方式
            return $this->redirect(['/admin/index/index']);

        }catch(Exception $e){
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'count' => 0,
                'data' => []
            ];
        }

    }

    //获取指定模型
    private static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = Article::findOne($id);
        if (!$model || $model === null){
            throw new NotFoundHttpException('没有找到相关数据。');
        }

        return $model;

    }

    //批量删除
    public function actionBatchDel(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('方法不被允许。');

            $ids = Yii::$app->request->post('data');
            if(empty($ids) || !is_array($ids))
                throw new BadRequestHttpException('请求参数错误。');

            $ids = array_diff(array_unique(array_map('intval', $ids)),[0]);

            //放入回收站
            if(Article::updateAll(['recycle'=>1],['in', 'id', $ids]) === false){
                throw new Exception('放置回收站失败，请重试。');
            }

            return ['errno'=>0, 'message'=>'放置回收站成功。'];


        }catch (Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }
}