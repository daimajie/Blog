<?php
namespace app\controllers;
use app\components\Helper;
use app\models\notebook\Notebook;
use Yii;
use yii\base\Exception;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

class NotebookController extends BaseController
{
    public $layout = 'layout-full';

    /**
     * 日记首页
     */
    public function actionIndex(){
        return $this->render('index',[
            'count' => Notebook::find()->count(), //文章总数（所有日记）
            'limit' => Yii::$app->params['pageSize'],//每页限数
        ]);
    }

    /**
     * ajax 获取日记列表
     */
    public function actionNotes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //检测方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //检测参数
            $curr = (int)Yii::$app->request->post('curr');
            $limit = (int)Yii::$app->request->post('limit');

            //获取数据
            $notes = Notebook::getNotes($curr,$limit);

            //转换头像
            $notes = Helper::photoInPlace($notes);

            //发送数据
            return ['errno'=>0,'data'=>$notes];
        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno' => 1,'data'=>[],'message' => $e->getMessage()];
        }


    }



}