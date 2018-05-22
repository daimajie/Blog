<?php
namespace app\controllers;
use app\models\content\Article;
use app\models\member\LoginForm;
use app\models\member\PasswordResetRequestForm;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use app\models\member\RegisterForm;
use Yii;
use app\components\Helper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use app\models\member\ResetPasswordForm;
use yii\filters\VerbFilter;

class IndexController extends BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    public function actions() {
        return [
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    //首页
    public function actionIndex(){
        $this->layout = 'layout-full';

        return $this->render('index',['pageSize'=>Yii::$app->params['pageSize']]);
    }

    //ajax获取首页文章列表
    public function actionGetArticles(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            //验证方法
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //验证参数
            $curr = (int)Yii::$app->request->post('curr');
            $limit = (int)Yii::$app->request->post('limit');

            //获取数据
            $articles = Article::getArticlesAll($curr,$limit);
            if(empty($articles))
                throw new NotFoundHttpException('暂无数据。');

            //转换头像
            $articles = Helper::photoInPlace($articles);

            //发送数据
            return ['errno'=>0,'data'=>$articles];

        }catch (MethodNotAllowedHttpException $e){
            return $this->redirect(['index/index']);
        }catch (Exception $e){
            return ['errno'=>1,'data'=>[], 'message'=>$e->getMessage()];
        }
    }


    //登陆
    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        $model = new LoginForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->login()){
                //登陆成功
                return $this->goBack();
            }
        }

        $model->password = '';
        return $this->render('login',[
            'model' => $model,
        ]);
    }

    //注册
    public function actionRegister(){
        $model = new RegisterForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->store()){
                Yii::$app->session->setFlash('success', '注册成功。');
                return $this->redirect(['login']);

            }
        }

        return $this->render('register',[
            'model' => $model,
        ]);
    }

    /**
     * 发送邮箱验证码
     */
    public function actionSendCaptcha(){
        try{
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $email = Yii::$app->request->post('email');
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                throw new BadRequestHttpException('请求参数错误。');
            }

            //生成验证码
            $captcha = Helper::generateCaptcha(6, 5, 'email-captcha');

            //发送验证码
            $view = 'message/email-captcha';
            $ret = Helper::sendEmail(
                Yii::$app->params['adminEmail'],
                $email,
                Yii::$app->name,
                $view,
                ['captcha'=>$captcha]
            );
            if($ret)
                return ['errno'=>0, 'message' => '邮件已成功发送。'];

            return ['errno'=>1, 'message' => '邮件发送失败，请稍后再试。'];
        }catch (MethodNotAllowedHttpException $e){

            return $this->redirect(['/index/index']);
        }catch (Exception $e){
            return ['errno' => 1, 'message'=>$e->getMessage()];
        }

    }

    /**
     * logout
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 申请重置密码
     */
    public function actionResetPasswordRequest(){
        $model = new PasswordResetRequestForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->sendEmail()){
                //发送邮件成功
                Yii::$app->session->setFlash('success', '发送邮件成功。');
                $model->username = $model->email = '';
            }
                //发送失败
                //Yii::$app->session->setFlash('fail', '发送失败，请重试。');
        }

        return $this->render('reset-password-request',[
            'model' => $model,
        ]);
    }
    
    /**
     * 重置密码
     * @params string #token凭证
     */
    public function actionResetPassword($token)
    {
        try{
            $model = new ResetPasswordForm($token);

            if(Yii::$app->request->isPost){
                if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
                    Yii::$app->session->setFlash('success', '新密码已设置成功.');
                    return $this->redirect(['index/login']);
                }
            }

            return $this->render('reset-password', [
                'model' => $model,
            ]);
        }catch(\Exception $e){
            throw new BadRequestHttpException('无效请求。');
        }

    }



}