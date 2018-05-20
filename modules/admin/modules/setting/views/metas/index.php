<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                SEO设置 - 为了能让搜索引擎搜索到，请规范详细填写站点元数据。
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
            <?= $form->field($model, 'sitename',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入站点名称",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>
            <?= $form->field($model, 'keywords',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入站点关键字",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>
            <?= $form->field($model, 'description', ['options'=>['tag'=>false]])->textarea([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder'=>"站点描述",
                'class'=>"layui-textarea"
            ])?>
            <?= $form->field($model, 'aboutme', ['options'=>['tag'=>false]])->textarea([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder'=>"关于我",
                'class'=>"layui-textarea",
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
layui.use(['form','layedit'], function(){
  var layedit = layui.layedit
  
});
JS;
$this->registerJs($str);
?>
