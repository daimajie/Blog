<?php
namespace app\controllers;
use app\components\Helper;
use app\models\content\Category;
use app\models\setting\Metas;
use yii\caching\DbDependency;
use yii\helpers\VarDumper;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();

    }

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){


            //获取分类信息
            $dependency = new DbDependency(['sql' => 'select count(*) from {{%category}}']);
            $cache = Yii::$app->cache;
            $cats = $cache->get('cats');
            if($cats === false){
                $cats = Category::find()->orderBy(['created_at'=>SORT_DESC])->limit(5)->asArray()->all();
                $cats = Helper::setSubNav($cats);
                $cache->set('cats', $cats, 3600, $dependency);
            }
            $this->view->params['cats'] = $cats;

            //获取网站信息
            $metas = $cache->get('metas');
            if($metas === false){
                $metas = Metas::find()->where(['id'=>1])->asArray()->one();
                $cache->set('metas',$metas,3600);
            }
            $this->view->params['metas'] = $metas;


            return true;

        }
    }


}