<?php
namespace app\modules\admin\modules\member\controllers;
use app\modules\admin\controllers\BaseController;
use app\modules\admin\modules\member\models\User;
use Yii;
use yii\web\MethodNotAllowedHttpException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\Pagination;
use yii\base\Exception;
use yii\db\Exception as DbException;

class UserController extends BaseController
{
    /**
     * 用户列表
     */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 用户添加
     */
    public function actionCreate(){
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->signin()){
                Yii::$app->session->setFlash('success', '添加用户成功。');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

    /**
     * 用户修改
     */
    public function actionUpdate($id){
        $model = self::getModel($id);
        $model->scenario = User::SCENARIO_UPDATE;
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->renew()){
                Yii::$app->session->setFlash('success', '修改用户成功。');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

    /**
     * 用户删除
     */
    public function actionDelete(){
        try {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            $id = (int)Yii::$app->request->post('id');

            $model = self::getModel($id);

            if ($model->delete() === false)
                throw new Exception('删除失败，请重试。');

            return ['errno' => 0, 'message' => '删除成功。'];
        }catch (DbException $e){
            return ['error' => 1, 'message' => '请先删除该分类下的所有标签。'];
        }catch(Exception $e){

            return ['errno'=>1, 'message' => $e->getMessage()];
        }


    }

    /**
     * 获取指定模型
     */
    public static function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');
        $model = User::findOne($id);
        if(!is_null($model))
            return $model;

        throw new NotFoundHttpException('没有相关数据');
    }

    /**
     * 表单数据
     */
    public function actionGetTableData($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许。');

            $query = User::find();
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
                ->orderBy(['created_at' => SORT_DESC,'id' => SORT_DESC])
                ->asArray()
                ->all();

            $author = ['阅读者','作者','管理'];
            foreach ($data as $key => &$val){
                $val['author'] = $author[$val['author']];
            }

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

            if(User::deleteAll(['in', 'id', $ids]) === false)
                throw new Exception('批量删除分类失败，请重试。');

            return ['errno' => 0, 'message'=>'批量删除分类成功'];
        }catch (DbException $e){
            return ['error' => 1, 'message' => '请先删除这些分类下的所有标签。'];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }
}