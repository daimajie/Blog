
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
                            <p class="margin-b-10 margin-t-10">重置密码</p>

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
                                'class'=>"layui-input",
                                'disabled'=>true
                            ]);?>
                            <?= $form->field($model,'password')->passwordInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请输入新密码",
                                'class'=>"layui-input"
                            ]);
                            ?>
                            <?= $form->field($model,'re_password')->passwordInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请确认密码",
                                'class'=>"layui-input"
                            ]);
                            ?>

                            <div>
                                <?= Html::submitButton('点击提交!', [
                                    'class' => 'layui-btn',
                                    'lay-submit'=>true,
                                    'lay-filter'=>"create-form",
                                    'id' => 'send-btn'
                                ]) ?>
                                <span class="float-r">
                                    <?= Html::tag('a','返回网站首页去登陆',['href'=>Url::to(['index/index'])])?>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <?= Html::tag('a','去登陆',['href'=>Url::to(['index/login'])])?>
                                </span>
                            </div>
                            <?php ActiveForm::end()?>
                        </div>
                        <div class="layui-col-sm4 form-r">
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

