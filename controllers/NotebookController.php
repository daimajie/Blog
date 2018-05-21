<?php
namespace app\controllers;
use app\components\Helper;
use app\models\notebook\Notebook;
use Yii;
use yii\helpers\VarDumper;

class NotebookController extends BaseController
{
    public $layout = 'layout-full';

    /**
     * 日记首页
     */
    public function actionIndex(){
        $model = new Notebook();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //添加日记成功
                Yii::$app->session->setFlash('success','添加日记成功。');
                return $this->refresh();
            }
        }

        //获取日记列表
        $notes = Notebook::getNotes();
        $notes['data'] = Helper::photoInPlace($notes['data']);
        //VarDumper::dump($notes['pagination'],10,1);die;
        return $this->render('index',[
            'model' => $model,
            'notes' => $notes['data'],
            'pagination' => $notes['pagination'],
        ]);
    }


}