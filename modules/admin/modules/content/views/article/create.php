<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="layui-fliud">
    <div class="layui-row">
        <!--content-->
        <div class="content">
            <blockquote class="site-text layui-elem-quote">
                <p>文明上网，理性发言。<em>赶紧写篇文章分享给小伙伴们吧。</em></p>
                <p>每一篇文章必须选择一个话题，一个话题可以创建若干标签，请规范输入各种分类名称。</p>
                <a href="javascript:history.go(-1);" class="layui-btn layui-btn-primary layui-btn-sm">返回</a>
            </blockquote>

            <hr>

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'layui-form'],
                'enableClientScript' => false,
                'fieldConfig' => [
                    'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}{error}</div></div>',
                    'labelOptions'=>['class'=>'layui-form-label'],
                ],

            ])?>

            <?=
                $form->field($model,'topic_id',[
                    'template'=>'{input}',
                ])->hiddenInput([]);
            ?>

            <?= $form->field($model, 'topic',[
                    'options'=>['tag'=>false],
                    'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}{error}<div id="search_topics"></div></div></div>',
            ])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "搜索话题",
                'autocomplete' => "off",
                'class' => "layui-input search_topic_btn"
            ])?>

            <?= $form->field($model, 'title',['options'=>['tag'=>false]])->textInput([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder' => "请输入标题",
                'autocomplete' => "off",
                'class' => "layui-input"
            ])?>

            <?= $form->field($model, 'brief', ['options'=>['tag'=>false]])->textarea([
                'required'=>true,
                'lay-verify' => "required",
                'placeholder'=>"请输入内容",
                'class'=>"layui-textarea"
            ])?>

            <div class="layui-form-item">
                <label class="layui-form-label">文章类型</label>
                <div class="layui-input-block">
                    <?php $type = $model->type;?>
                    <input type="radio" name="Article[type]" value="1" title="原创" <?= ($type == 1 || ($type != 2 && $type != 3))? 'checked' : '';?>>
                    <input type="radio" name="Article[type]" value="2" title="转载" <?= $type == 2? 'checked' : '';?>>
                    <input type="radio" name="Article[type]" value="3" title="翻译" <?= $type == 3? 'checked' : '';?>>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">发布</label>
                <div class="layui-input-block">
                    <?php $draft = $model->draft?>
                    <input type="radio" name="Article[draft]" value="0" title="直接发布" <?= $draft != 1 ? 'checked' : '';?>>
                    <input type="radio" name="Article[draft]" value="1" title="存为草稿" <?= $draft == 1 ? 'checked' : '';?>>
                </div>
            </div>

            <?=
                $form->field($model, 'pri_content',['options'=>['tag'=>false]])->textarea([

                        'class'=>"layui-textarea",
                        'style'=>"display: none",
                ]);
            ?>


            <div class="layui-form-item">
                <label class="layui-form-label">可用标签</label>
                <div class="layui-input-block" id="tag_list">
                    <?php
                    if(!empty($tags)):
                    foreach($tags as $k => $v):
                        ?>
                    <?php $isChecked = !empty($model->tags) && in_array($v['id'], $model->tags) ? 'checked' : '';?>
                    <input type="checkbox" name="Article[tags][]" title="<?= $v['name']?>" value="<?= $v['id']?>" <?= $isChecked?>>
                    <?php
                    endforeach;
                    else:
                    echo '<span class="text-green">暂无可用标签，可选择创建标签。</span>';
                    endif;
                    ?>
                </div>
            </div>

            <?= $form->field($model, 'pri_tags',[
                'options'=>['tag'=>false],
                //'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}{error}<div id="search_topics"></div></div></div>',
            ])->textInput([
                'placeholder' => "如果创建多个标签，请用逗号分割。",
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
<script id="topics" type="text/html">
    <ul>
        {{#  layui.each(d.data, function(index, item){ }}
        <li data-tid="{{ item.id }}">
            <span>{{ item.name }}</span>
        </li>
        {{#  }); }}
        {{#  if(d.data.length === 0){ }}
        无数据
        {{#  } }}
    </ul>
</script>
<!--layui-form-checked-->
<script id="tags" type="text/html">
        {{#  layui.each(d.data, function(index, item){ }}
        <input type="checkbox" name="Article[tags][]" title="{{ item.name }}" value="{{ item.id }}" {{ item.checked }}>
        {{#  }); }}
        {{#  if(d.data.length === 0){ }}
        <span class="text-green">暂无可用标签，可选择创建标签。</span>
        {{#  } }}
</script>
<?php
$aid = $model->isNewRecord ? 0 : $model->id; //当前文章id
$token =  \Yii::$app->request->getCsrfToken();
$str = <<<JS
    layui.use(['form','layedit','laytpl','layer'], function(){
      var layedit = layui.layedit
      ,$ = layui.jquery
      ,laytpl = layui.laytpl
      ,form = layui.form
      ,layer = layui.layer;
      
      //构建一个默认的编辑器
      var index = layedit.build('article-pri_content');
      
      var fun = {
          requestTags : function(tid){
              $.ajax({
                    url : UrlManager.createUrl('/admin/content/tag/request-tags'),
                    data : {'data' : tid, 'aid': "$aid", '_csrf': "$token"},
                    type : 'post',
                    success : function(d){
                        if(d.errno == 0){
                            
                            var tagsTpl = tags.innerHTML
                                ,view = $('#tag_list')[0];
                                laytpl(tagsTpl).render(d, function(html){
                                  view.innerHTML = html;
                                });
                                form.render();
                                
                        }else{
                            layer.msg(d.message);
                        }
                            
                    }
              });
          }
      };
      
      
      //搜索topic
      $('.search_topic_btn').on('keyup',function(){
          $('#article-topic_id').val(0);
          var val = $(this).val();
          if(val.length <= 0)
              return;
          $.ajax({
                url : UrlManager.createUrl('/admin/content/topic/search-topics'),
                type : 'post',
                data : {'val': val, '_csrf' : "$token"},
                success : function(d){
                    var getTpl = topics.innerHTML
                    ,view = $('#search_topics')[0];
                    
                    laytpl(getTpl).render(d, function(html){
                        
                        view.innerHTML = html;
                    });
                }
                
          });
      });
      
      //填充输入框
      $('#search_topics').on('click', 'li', function(){
          var idInput = $('#article-topic_id')
              ,toInput = $('#article-topic')
              ,tid = $(this).data('tid')
              ,val = $(this).find('span').text();
          
          idInput.val(tid);
          toInput.val(val);
          $('#search_topics').empty();
          
          //选择话题之后 展示该话题下所有可用标签
          fun.requestTags(tid);
          
      });
      
    });
JS;
$this->registerJs($str);


?>