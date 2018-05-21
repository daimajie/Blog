<li style="padding:5px;margin-bottom: 20px;background-color: #efefef">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'layui-form'],
        'enableClientScript' => false,
        'fieldConfig' => [
            'template' => '{input}',
        ],

    ])?>
    <?= $form->field($model, 'content', ['options'=>['tag'=>false]])->textarea([
        'class'=>"layui-textarea",
        'id' =>'editor',
    ])?>
    <?= Html::submitButton('<i class="layui-icon">&#xe609;发射</i>', [
        'class' => 'layui-btn layui-btn-danger layui-btn-sm float-r',
        'lay-submit'=>true,
    ]) ?>
    <?php ActiveForm::end()?>
    <div class="layui-clear"></div>
</li>



<?php
if(!empty($notes)):
    foreach($notes as $item):
        ?>
        <li class="artitem">
            <div class="contop">
                <div class="photo float-l">
                    <a href="#"><img src="<?= $item['user']['photo']?>" alt="<?= $item['user']['username']?>"></a>
                </div>
                <div class="info float-l">
                    <p class="layui-word-aux font-bold font-14"><?= $item['user']['username']?></p>
                    <p class="layui-word-aux font-14"><?= View::timeFormat($item['created_at'])?></p>
                </div>
            </div>
            <div class="conmid">
                <p class="margin-b-10"><?= $item['content']?></p>
            </div>
        </li>
    <?php
    endforeach;
endif;
?>