<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                写一篇日记记录现在的心情和生活把
            </blockquote>
            <div class="space-5"></div>
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'layui-form'],
                'enableClientScript' => false,
                'fieldConfig' => [
                    'labelOptions'=>['class'=>'layui-form-label'],
                ],

            ])?>
            <?=
            $form->field($model, 'content',['options'=>['tag'=>false]])->textarea([
                'class'=>"layui-textarea",
                'style'=>"display: none",
            ]);
            ?>



            <div class="layui-form-item">
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
            <?php ActiveForm::end()?>
        </div>
        <!--content-->


    </div>
</div>
<?php
$str = <<<JS
layui.use(['form','layedit'], function(){
  var layedit = layui.layedit;
  
  //构建一个默认的编辑器
  var index = layedit.build('notebook-content');
  
});
JS;
$this->registerJs($str);
?>
