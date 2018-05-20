<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'layui-form'],
                'enableClientScript' => false,
                'fieldConfig' => [
                    'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}{error}</div></div>',
                    'labelOptions'=>['class'=>'layui-form-label'],
                ],

            ])?>

            <!--隐藏话题ID-->
            <?= $form->field($model, 'topic_id', [
                'options'=>[
                    'tag'=>false,
                ],
                'template' => '{input}',
                ])->hiddenInput()->label(false);
            ?>

            <?= $form->field($model, 'name',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入标签名",
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
