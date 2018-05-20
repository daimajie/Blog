<?php
namespace app\modules\admin\modules\content\controllers;

use app\models\content\Category;
use app\models\content\Topic;
use app\modules\admin\controllers\BaseController;
use \Yii;
use yii\base\Exception;
use yii\db\Exception as DbException;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\web\Response;

class CategoryController extends BaseController
{

    /**
     * 分类列表
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 分类添加
     */
    public function actionCreate(){
        $model = new Category();

        //接受post
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //创建分类成功
                Yii::$app->session->setFlash('success', '创建分类成功。');
                $model->name = $model->desc = '';//清空数据
            }
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 分类修改
     */
    public function actionUpdate($id){
        $model = self::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //修改成功、
                Yii::$app->session->setFlash('success','修改分类成功。');
                $model->name = $model->desc = '';
            }else
                //修改失败
                Yii::$app->session->setFlash('fail','修改分类失败，请重试。');
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 分类删除
     */
    public function actionDelete(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {

            if (!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            $id = (int)Yii::$app->request->post('id');

            $model = self::getModel($id);

            //检测是否可删除(是否存在话题)
            if(Topic::getTopicsCountById($model->id))
                throw new DbException('请先删除该分类下的所有话题');

            if ($model->delete() === false)
                throw new Exception('删除失败，请重试。');

            return ['errno' => 0, 'message' => '删除成功。'];
        }catch(Exception $e){
            return ['errno'=>1, 'message' => $e->getMessage()];
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


            $query = Category::find();
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
                ->orderBy(['id' => SORT_DESC])
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
     * 获取指定分类模型
     */
    private static function getModel($id){
           $id = (int) $id;
           if($id <= 0)
               throw new BadRequestHttpException('请求参数错误。');

           $model = Category::findOne($id);
           if (!$model || $model === null){
               throw new NotFoundHttpException('没有找到相关数据。');
           }
           return $model;
    }
    
    /**
     * 批量删除
     */
    public function actionBatchDel(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $ids = Yii::$app->request->post('data');
            if (empty($ids) || !is_array($ids))
                throw new BadRequestHttpException('请求参数错误。');


            $ids = array_diff(array_unique(array_map('intval', $ids)),[0]);


            //检测是否可删除(是否存在话题)
            if(Topic::getTopicsCountById($ids))
                throw new DbException('请先删除这些分类下的所有话题');

            if (Category::deleteAll(['in', 'id', $ids]) === false)
                throw new Exception('批量删除分类失败，请重试。');

            return ['errno' => 0, 'message' => '批量删除分类成功'];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

}
