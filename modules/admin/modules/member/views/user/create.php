<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                <p><?= $model->isNewRecord ? '新建用户' : '编辑用户';?> - <em>请规范输入用户名及昵称</em></p>
                <a href="javascript:history.go(-1);" class="layui-btn layui-btn-primary layui-btn-sm">返回</a>
            </blockquote>
            <div class="space-5"></div>
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'layui-form'],
                'enableClientScript' => false,
                'fieldConfig' => [
                    'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}{error}</div></div>',
                    'labelOptions'=>['class'=>'layui-form-label'],
                ],

            ])?>
            <?= $form->field($model, 'username',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入用户名",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>

            <?= $form->field($model, 'password',['options'=>['tag'=>false]])->passwordInput([
                //'required'=>true,
                //'lay-verify' => "required",
                'placeholder' => "请输入密码",
                'class' => "layui-input"
            ])->label('密码')?>

            <?= $form->field($model, 're_password',['options'=>['tag'=>false]])->passwordInput([
                //'required'=>true,
                //'lay-verify' => "required",
                'placeholder' => "请输入密码",
                'class' => "layui-input"
            ])->label('重复密码')?>

            <?= $form->field($model, 'email',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入邮箱",
                'class' => "layui-input"
            ])?>

            <div class="layui-form-item">
                <label class="layui-form-label">标记为作者</label>
                <div class="layui-input-block">
                    <?php $role = $model->author?>
                    <input type="radio" name="User[author]" value="0" title="阅读者" <?= $role ===0 || ($role !==1 && $role !==2) ? 'checked' : '';?>>
                    <input type="radio" name="User[author]" value="1" title="作者" <?= $role ===1 ? 'checked' : '';?>>
                    <input type="radio" name="User[author]" value="2" title="管理" <?= $role ===2 ? 'checked' : '';?>>
                </div>
            </div>

            <?= $form->field($model, 'nickname',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入昵称",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <?= Html::submitButton('立即提交!', [
                        'class' => 'layui-btn',
                        'lay-submit'=>true,
                        'lay-filter'=>"create-form"
                    ]) ?>
                    <?= Html::button('重置!', [
                        'class' => 'layui-btn layui-btn-primary',
                        'type'=>"reset"
                    ]) ?>
                </div>
            </div>
            <?php ActiveForm::end()?>
        </div>
        <!--content-->


    </div>
</div>
<?php
$str = <<<JS
layui.use(['form'], function(){
  
  
});
JS;
$this->registerJs($str);
?>
