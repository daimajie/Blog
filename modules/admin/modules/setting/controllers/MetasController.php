<?php
namespace app\modules\admin\modules\setting\controllers;
use app\modules\admin\controllers\BaseController;
use app\models\setting\Metas;
use Yii;

class MetasController extends BaseController
{
    /**
     * 元信息设置页
     */
    public function actionIndex(){
        $model = self::getModel();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //编辑成功
                Yii::$app->session->setFlash('success','保存站点信息成功。');
                return $this->refresh();
            }
        }

        return $this->render('index',[
            'model' => $model,
        ]);
    }

    private static function getModel(){
        return $model = Metas::findOne(1);
    }
}