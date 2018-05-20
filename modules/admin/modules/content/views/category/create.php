<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                文章分类 - 一般作为文章的划分 如：生活日记 工作日志 技术文章 ... 等分类模式
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
                    'placeholder' => "请输入标题",
                    'autocomplete' => "off",
                    'class' => "layui-input"
                ])?>

                <?= $form->field($model, 'desc', ['options'=>['tag'=>false]])->textarea([
                        'placeholder'=>"请输入内容",
                        'class'=>"layui-textarea"
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
