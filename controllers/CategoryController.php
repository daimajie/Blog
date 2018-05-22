<?php
namespace app\controllers;

use app\models\content\Category;
use app\models\content\Topic;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoryController extends BaseController
{
    public $layout = 'layout';

    /**
     * 分类首页
     */
    public function actionIndex(){


        return $this->render('index',[
            'count' => Category::find()->count(),
            'limit' => Yii::$app->params['pageSize'],
        ]);
    }

    /**
     * 分类详情
     */
    public function actionDetail($category_id){
        $category_id = (int)$category_id;

        if($category_id <= 0)
            throw new BadRequestHttpException('请求参数错误。');

        if(!$category = Category::findOne($category_id)){
            throw new NotFoundHttpException('没有相关数据。');
        }

        //获取话题列表
        $topics = Topic::find()->where(['category_id'=>$category_id])->asArray()->all();



        return $this->render('detail',[
            'category' => $category,
            'topics' => $topics
        ]);
    }

    /**
     * 获取分页后的分类数据列表
     */
    public function actionGetCategory(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            //检测请求方式
            if (!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //获取请求参数
            $curr = (int)Yii::$app->request->post('curr');
            $limit = (int)Yii::$app->request->post('limit');
            if ($curr <= 0 || $limit <= 0) {
                throw new BadRequestHttpException('请求参数错误。');
            }

            //获取分类列表
            $category = Category::getCategoryAll($curr, $limit);
            if (!$category)
                throw new NotFoundHttpException('没有数据。');

            //发送分类列表
            return ['errno' => 0, 'data' => $category];
        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno'=>1,'data'=>[],'message'=>$e->getMessage()];
        }
    }
}