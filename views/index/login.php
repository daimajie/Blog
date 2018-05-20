
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\home\AppAsset;

AppAsset::addCss($this,"static/home/css/sign.css");
?>
<!-- 登录 -->
<section class="signform">
    <div class="layui-container">
        <div class="layui-row">
            <div class="wrap layui-col-xs12 layui-col-md8 layui-col-md-offset2">
                <div class="form">
                    <h3><?= Yii::$app->name?></h3>
                    <hr class="layui-bg-gray">
                    <div class="layui-row">
                        <div class="layui-col-sm8 form-l">
                            <p class="margin-b-10 margin-t-10">用户登录</p>

                            <?php $form = ActiveForm::begin([
                                'options' => ['class' => 'layui-form'],
                                'enableClientScript' => false,
                                'fieldConfig' => [
                                    'template' => '<div class="layui-form-item" pane>{label}<div class="layui-input-block">{input}{error}</div></div>',
                                    'labelOptions'=>['class'=>'layui-form-label'],

                                ],
                                'errorCssClass' => "error-color",
                            ])?>
                            <?= $form->field($model,'username')->textInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请输入用户名/邮箱",
                                'class'=>"layui-input"
                            ]);
                            ?>
                            <?= $form->field($model,'password')->passwordInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请输入密码",
                                'class'=>"layui-input"
                            ]);
                            ?>
                            <?= $form->field($model,'captcha',[

                            ])->widget(yii\captcha\Captcha::className(),[
                                'captchaAction'=>'captcha',
                                'imageOptions'=>[
                                    'alt'=>'点击换图',
                                    'title'=>'点击换图',
                                    'style'=>'cursor:pointer'
                                ],
                                'options' => ['class'=>"layui-input layui-input-inline"],
                                'template' => '{image}{input}',
                            ]);?>

                            <div class="layui-form-item" pane="">
                                <label class="layui-form-label">7天免登录</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="Login['rememberMe']" lay-skin="primary" <?= $model->rememberMe ? 'checked' : '';?>>
                                </div>
                            </div>

                            <div>
                                <?= Html::submitButton('立即提交!', [
                                    'class' => 'layui-btn',
                                    'lay-submit'=>true,
                                    'lay-filter'=>"create-form"
                                ]) ?>
                                <span class="float-r">
                                    <?= Html::tag('a','暂无账号，去注册。',['href'=>Url::to(['index/register'])])?>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?= Html::tag('a','忘记密码',['href'=>Url::to(['index/reset-password-request'])])?>
                                </span>
                            </div>
                            <?php ActiveForm::end()?>
                        </div>
                        <div class="layui-col-sm4 form-r">
                            <p class="margin-b-10 margin-t-10">第三方登录</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$token =  \Yii::$app->request->getCsrfToken();
$jsStr = <<<JS
    layui.config({
        base: 'static/home/js/'
    }).use(['layer', 'element', 'form'], function (exports) {
        
        
    
    
        
    });
JS;
$this->registerJs($jsStr);
?>

