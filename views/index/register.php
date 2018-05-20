
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\home\AppAsset;

AppAsset::addCss($this,"static/home/css/sign.css");
?>
<!-- 登录 -->
<section class="signform">
    <div class="layui-container">
        <div class="layui-row">
            <div class="wrap layui-col-xs12 layui-col-md8 layui-col-md-offset2">
                <div class="form">
                    <h3><?= Html::encode(Yii::$app->name)?></h3>
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
                                    'placeholder'=>"请输入用户名",
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
                            <?= $form->field($model,'re_password')->passwordInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"重复密码",
                                'class'=>"layui-input"
                            ]);
                            ?>
                            <?= $form->field($model,'email',[
                                    'options' => ['id'=>'check-mail'],
                                    'template' => '<div class="layui-form-item" pane>{label}<div class="layui-input-inline">{input}{error}</div><a class="layui-btn layui-btn-primary send-btn">发送验证码</a></div>',
                            ])->textInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请输入邮箱",
                                'class'=>"layui-input"
                            ]);
                            ?>
                            <?= $form->field($model,'captcha')->textInput([
                                'required'=>true,
                                'lay-verify' => "required",

                                'autocomplete'=>"off",
                                'placeholder'=>"请输入邮箱验证码",
                                'class'=>"layui-input"
                            ]);
                            ?>

                            <div>
                                <?= Html::submitButton('立即提交!', [
                                    'class' => 'layui-btn',
                                    'lay-submit'=>true,
                                    'lay-filter'=>"create-form"
                                ]) ?>
                                <span class="float-r">
                                    <?= Html::tag('a','已有账户,立即登录',['href'=>Url::to(['index/login'])])?>
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
        var layer = layui.layer
        ,element = layui.element
        ,form = layui.form
        ,timer = null;
        
        
        /*函数*/
        var fun = {
            'setTimer' : function(ele){
                clearTimeout(timer);
                ele.addClass('layui-btn-disabled');
                var num = 59;
                timer = setInterval(function(){
                    ele.text('邮件已发送...' + num--);
                    if(num < 0){
                        clearTimeout(timer);
                        ele.removeClass('layui-btn-disabled').text('发送验证码');
                    }
                },1000);
            }
        };
        /*发送邮件*/
        var emailWrap = $('#check-mail')
            ,sendBtn = emailWrap.find('.send-btn')
            ,emailInput = emailWrap.find('input');
        sendBtn.on('click',function(){
            if($(this).hasClass('layui-btn-disabled'))
                return;
            
            var val = emailInput.val();
            if(typeof val !== 'string' || val.length <= 0)
                return false;
            $.ajax({
                url : UrlManager.createUrl('/index/send-captcha'),
                type : 'post',
                data : {'email' : val, '_csrf': ". $token ."},
                success : function(d){
                    if(d.errno === 0){
                        //发送成功
                        fun.setTimer(sendBtn);
                    }else{
                        //发送失败
                        layer.msg(d.message);
                        sendBtn.addClass('layui-btn-disabled');
                        setTimeout(function(){
                            sendBtn.removeClass('layui-btn-disabled');
                        },3000);
                        
                    }
                }
            });
        });
        
        
    
    
        
    });
JS;
$this->registerJs($jsStr);
?>


