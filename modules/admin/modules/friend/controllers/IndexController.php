<?php
namespace app\modules\admin\modules\friend\controllers;
use app\modules\admin\controllers\BaseController;
use app\models\friend\Friend;
use Yii;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class IndexController extends BaseController
{
    /**
     * 友链列表
     */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 友情链接添加
     */
    public function actionCreate(){
        $model = new Friend();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //添加成功
                Yii::$app->session->setFlash('success', '添加友情链接成功。');
                $model = new Friend();
            }
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 友情链接编辑
     */
    public function actionUpdate($id){
        $model = self::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '编辑友情链接成功。');
                $model->name = $model->url = '';
            }
        }


        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 友情链接删除
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
     * 表格数据
     */
    public function actionGetTableData($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许。');


            $query = Friend::find();
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);

            //配置当前页码
            list($page, $limit) = [(int)$page, (int)$limit];
            if ($page < 0 || $limit < 0)
                throw new BadRequestHttpException('请求参数错误。');
            $pagination->setPageSize($limit);
            $pagination->setPage($page - 1);


            //获取数据
            $data = $query
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy(['sort'=>SORT_ASC, 'id' => SORT_DESC])
                ->all();

            if (empty($data))
                throw new NotFoundHttpException('没有相关数据。');

            return [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $data
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

    /**
     * 批量删除
     */
    public function actionBatchDel(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $ids = Yii::$app->request->post('data');
            if(empty($ids) || !is_array($ids))
                throw new BadRequestHttpException('请求参数错误。');

            $ids = array_diff(array_unique(array_map('intval', $ids)),[0]);


            if(Friend::deleteAll(['in', 'id', $ids]) === false)
                throw new Exception('批量删除友情链接失败，请重试。');

            return ['errno' => 0, 'message'=>'批量删除友情链接成功'];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    /**
     * 获取模型
     */
    private static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        if(!$model = Friend::findOne($id))
            throw new NotFoundHttpException('没有相关数据。');

        return $model;

    }
}