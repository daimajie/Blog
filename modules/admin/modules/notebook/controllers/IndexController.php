<?php
namespace app\modules\admin\modules\notebook\controllers;
use app\components\Helper;
use app\models\notebook\Notebook;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\base\Exception;

class IndexController extends BaseController
{
    /**
     * 日记首页
     */
    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * 日记创建
     */
    public function actionCreate(){
        $model = new Notebook();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '创建日记成功。');
                return $this->refresh();
            }
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

    /**
     * 日记编辑
     */
    public function actionUpdate($id){
        $model = self::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '编辑日记成功。');
                return $this->refresh();
            }
        }

        return $this->render('create',[
            'model' => $model,
        ]);
    }

    /**
     * 日记删除
     */
    public function actionDelete($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {

            if (!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            $id = Yii::$app->request->post('id');

            $model = self::getModel($id);

            if ($model->delete() === false)
                throw new Exception('删除失败，请重试。');

            return ['errno' => 0, 'message' => '删除成功。'];
        }catch(Exception $e){

            return ['errno'=>1, 'message' => $e->getMessage()];
        }

    }

    /**
     * 日记模型获取
     */
    private static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');
        $model = Notebook::findOne($id);
        if(empty($model))
            throw new NotFoundHttpException('没有相关数据。');
        return $model;
    }

    /**
     * 表格数据
     */
    public function actionGetTableData($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许。');


            $query = Notebook::find();
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
                ->with('user')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy(['created_at'=>SORT_DESC,'id' => SORT_DESC])
                ->asArray()
                ->all();

            if (empty($data))
                throw new NotFoundHttpException('没有相关数据。');
            foreach($data as $key => &$val){
                $val['username'] = $val['user']['username'];
                $val['content'] = Helper::truncate_utf8_string($val['content'],55);
            }

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

            if(Notebook::deleteAll(['in', 'id', $ids]) === false)
                throw new Exception('批量删除分类失败，请重试。');

            return ['errno' => 0, 'message'=>'批量删除分类成功'];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }


}