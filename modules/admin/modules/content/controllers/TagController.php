<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Tag;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagController extends BaseController
{

    /**
     * 标签创建
     */
    public function actionCreate($topic_id=1){
        $topic_id = (int)$topic_id;
        if($topic_id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = new Tag();
        $model->topic_id = $topic_id;

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '添加标签成功。');
                return $this->refresh();
            }
        }


        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 标签修改
     */
    public function actionUpdate($id){
        $model = self::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '修改标签成功。');
                return $this->refresh();
            }
        }

        return $this->render('create',[
            'model' =>$model,
        ]);
    }

    /**
     * 标签删除
     */
    public function actionDelete($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许。');
            }

            $model = self::getModel($id);

            if($model->delete() === false){
                throw new Exception('删除失败，清重试。');
            }

            return ['errno'=>0,'message'=>'删除成功。'];
        }catch(Exception $e){
            return ['errno'=>1,'message'=>$e->getMessage()];
        }catch(\Throwable $e){
            return ['errno'=>1,'message'=>$e->getMessage()];
        }

    }


    /**
     * 获取指定模型
     */
    private function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        $model = Tag::findOne(['id' => $id]);
        if(is_null($model))
            throw new NotFoundHttpException('没有相关数据。');

        return $model;
    }
    
    /**
     * ajax请求指定话题下的所有标签
     */
    public function actionRequestTags(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $tid = (int)Yii::$app->request->post('data');
            $aid = (int)Yii::$app->request->post('aid');

            if($tid <= 0)
                throw new BadRequestHttpException('请求参数错误。');
            $tags = Tag::getTagsById($tid, $aid);



            return ['errno' => 0, 'data'=>$tags];
        }catch (Exception $e){

            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

}