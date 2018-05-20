<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                友情链接 - 请指定友链的名称及地址，排序数字越小越靠前。
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
            <?= $form->field($model, 'name',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入名称",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>
            <?= $form->field($model, 'url',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入地址",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>
            <?= $form->field($model, 'sort',['options'=>['tag'=>false]])->textInput([
                'placeholder' => "请输入排序数字",
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
