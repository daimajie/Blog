<?php
namespace app\modules\admin\modules\content\controllers;
use app\models\content\Category;
use app\models\content\Tag;
use app\models\content\Topic;
use app\modules\admin\controllers\BaseController;
use Yii;
use yii\base\Exception;
use yii\db\Exception as DbException;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class TopicController extends BaseController
{
    /**
     * 话题列表
     */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 话题创建
     */
    public function actionCreate(){
        $model = new Topic();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '创建话题成功。');
                $model->name = $model->desc = '';
                $model->id = $model->category_id = 0;
            }

        }

        return $this->render('create',[
            'model' => $model,
            'categorys' => ArrayHelper::map(Category::find()->select(['id','name'])->asArray()->all(),'id', 'name'),
        ]);
    }
    
    /**
     * 话题修改
     */
    public function actionUpdate($id){
        $model = self::getModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //修改成功
                Yii::$app->session->setFlash('success','修改话题成功。');
                $model = new Topic();
            }
        }

        return $this->render('create',[
            'model' => $model,
            'categorys' => ArrayHelper::map(Category::find()->select(['id','name'])->asArray()->all(),'id', 'name'),
        ]);
    }
    
    /**
     * 话题删除
     */
    public function actionDelete(){
        try {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (!Yii::$app->request->isAjax)
                throw new BadRequestHttpException('请求方式不被允许。');

            $id = (int)Yii::$app->request->post('id');

            $model = self::getModel($id);


            //检测是否可以删除
            if (Tag::getTagsCountById($model->id))
                throw new DbException('请先删除该话题下的所有标签');


            if ($model->delete() === false)
                throw new Exception('删除失败，请重试。');

            return ['errno' => 0, 'message' => '删除成功。'];
        }catch(Exception $e){

            return ['errno'=>1, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * 话题批量删除
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

            //检测是否可以删除
            if (Tag::getTagsCountById($ids))
                throw new DbException('请先删除这些话题下的所有标签');

            if(Topic::deleteAll(['in', 'id', $ids]) === false)
                throw new Exception('批量删除分类失败，请重试。');

            return ['errno' => 0, 'message'=>'批量删除分类成功'];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }
    }

    /**
     * 获取指定模型
     */
    private function getModel($id){
        $id = (int)$id;
        if($id <= 0)
            throw new BadRequestHttpException('请求参数错误。');
        $model = Topic::findOne($id);
        if (!$model || $model === null)
            throw new NotFoundHttpException('没有相关数据。');

        return $model;

    }

    /**
     * 表单数据
     */
    public function actionGetTableData($page=1, $limit=10){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方法不被允许。');

            $query = Topic::find();
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
                ->with('category')
                ->with('tags')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->orderBy(['created_at' => SORT_DESC,'id' => SORT_DESC])
                ->asArray()
                ->all();

            //规范数据
            //$data = Topic::NormData($data);
            foreach($data as $k => &$v){
                $v['category'] = $v['category']['name'];
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

    public function actionSearchTopics(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            $val = Yii::$app->request->post('val');

            if(is_null($val))
                throw new BadRequestHttpException('请求参数错误。');

            $data = Topic::find()
                ->select(['id','name'])
                ->where(['like', 'name', $val])
                ->asArray()
                ->limit(10)
                ->all();

            if(is_null($data))
                throw new NotFoundHttpException('没有相关数据。');

            return ['errno'=>0, 'data'=>$data];
        }catch(Exception $e){
            return ['errno'=>1, 'message'=>$e->getMessage()];
        }


    }


}