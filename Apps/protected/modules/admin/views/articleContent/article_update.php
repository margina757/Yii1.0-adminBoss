<?php
$this->title = '文章信息修改 - ' . $this->titleTail;
?>
<style type="text/css">
	.soft-content {
		border: 1px solid #d2d2d2;
		width: 100%;
		overflow: auto;
	}
	.soft-content li {
		border-bottom: 1px dashed #d2d2d2;
		overflow: hidden;
		min-height: 30px;
	}
	.soft-content li.fir{
		line-height: 30px;
		text-indent: 10px;
	}
	.soft-content li div.control-group{
		float: left;
		min-width: 50%;
		margin-left: -40px; 
		margin-top:5px; 
	}
	#color-box{
		width:112px; 
		height: 100px; 
		position: absolute;
		display: none;
	}
	#color-box ul{
		border: 1px solid #d2d2d2;
		overflow: hidden;
	}
	#color-box ul li{
		margin-left: 2px;
		margin-top: 2px;
		float: left;
		display: inline-block;
		width: 20px;
		height: 20px;
		text-align: center;
	}

</style>
<div class="containner" style="position: relative;">
	<legend>
		<h2>文章信息修改</h2>
	</legend>
	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
			'helpType' => TbHtml::HELP_TYPE_INLINE,
			'htmlOptions' => array('enctype'=>'multipart/form-data'),
		));
	?>
	<fieldset>
		<ul class="soft-content">
			<li class="fir">文章信息</li>
			<li>
				<?php echo $form->textFieldControlGroup($model,'title',array(
					'size' => TbHtml::INPUT_SIZE_XLARGE,
					'label'=>'文章标题：',
				));?>

				<?php echo $form->textFieldControlGroup($model,'shorttitle',array(
					'label'=>'简略标题：',
				));?>
			</li>

			<li>
				<div class="control-group">
					<label class="control-label">自定义属性： </label>
					<div class="controls">
						<input  type="checkbox" name="ArticleContentModel[flag][]"  value="h" <?php echo in_array('h',$flag) ? 'checked' : '';?>>头条[h]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]"  value="c" <?php echo in_array('c',$flag) ? 'checked' : '';?>>推荐[c]&nbsp;
						<input type="checkbox" name="ArticleContentModel[flag][]" value="p" <?php echo in_array('p',$flag) ? 'checked' : '';?>>图片[p]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]" value="f" <?php echo in_array('f',$flag) ? 'checked' : '';?>>幻灯[f]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]" value="s" <?php echo in_array('s',$flag) ? 'checked' : '';?>>滚动[s]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]" value="j" <?php echo in_array('j',$flag) ? 'checked' : '';?>>跳转[j]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]" value="a" <?php echo in_array('a',$flag) ? 'checked' : '';?>>图文[a]&nbsp;
						<input  type="checkbox" name="ArticleContentModel[flag][]" value="b" <?php echo in_array('b',$flag) ? 'checked' : '';?>>加粗[b]
					</div>
				</div>
			</li>

			<li>
				<?php echo $form->dropDownListControlGroup($model,'cid',$channelList,array(
					'empty'=>'请选择文章所属栏目',
					'label' => '文章栏目：',
				));?>

				<div class="control-group" id="digital-box" style="display:none;">
					<label class="control-label" for="DigitalResourcesModel_url">资源链接地址：</label>
					<div class="controls">
						<input name="DigitalResourcesModel[url]" id="DigitalResourcesModel_url" type="text" maxlength="200" value="<?php if($digitalResourcesModel){echo $digitalResourcesModel->url;}?>">
						<span class="help-inline">地址例如：http://www.example.com/...</span>
					</div>
				</div>
			</li>

			<li>
				<?php echo $form->inlineradioButtonListControlGroup($model,'notpost',array(
					0=>'允许',
					1=>'不允许'
				),array(
					'label' =>'是否允许评论：',
				));?>

				<?php echo $form->textFieldControlGroup($model,'color',array(
					'size' => TbHtml::INPUT_SIZE_LARGE,
					'label'=>' 标题颜色：',
				));?>
			</li>

			<li>
				<?php echo $form->textFieldControlGroup($model,'writer',array(
					'label'=>'作者：',
				));?>

				<div class="control-group">
					<label class="control-label">发布时间：</label>
					<div class="controls">
					<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
						'name' => 'ArticleContentModel[created]',
						'pluginOptions' => array(
							'format' => 'yyyy-MM-dd hh:mm:ss'
						),
						'htmlOptions' => array(
							'placeholder' => '填写发布时间,默认当前系统时间',
							'class' => 'input-medium',
							'style' =>'width:200px;'
						),
				            ));
				            ?>
				            </div>
				</div>
			</li>

			<li>
				<?php echo $form->fileFieldControlGroup($model, 'litpic',array(
					'label'=>'缩略图：',
				)); ?>
				<?php 
					if($model->litpic){
						echo '<img style="width:150px;height:100px;" src="'.$model->litpic.'">';
					}
				?>
			</li>			

			<li>
				<?php echo $form->textFieldControlGroup($model,'keywords',array(
					'size' => TbHtml::INPUT_SIZE_XLARGE,
					'label'=>' 关键词：',
					'help' => '不填写程序自动提取5个关键词，手动填写用","分开',
				));?>
			</li>

			<li>
				<?php echo $form->textAreaControlGroup($model, 'description',array(
					'span' => 8,
					'rows' => 5,
					'label' => '内容摘要：',
				)); ?>
			</li>

			<li>
				<?php echo $form->textAreaControlGroup($model,'body',array(
					'id'=>'ArticleContentModel_body',
					'label' => '软件详细介绍：',
					'style' => 'width:98%;height:300px;',
				));?>
			</li>

			<li>
				<?php echo TbHtml::formActions(array(
					TbHtml::submitButton('确定', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
					TbHtml::resetButton('重置',array('class'=>'btn-reset')),
				)); ?>
			</li>
		</ul>
		<div id="color-box">
			<ul>
				<?php 
					foreach (Yii::app()->params['colorList'] as $row) {
						if($row == '#FFFFFF'){
							echo '<li style="background:'.$row.'" data-color="'.$row.'" class="color-none">N</li>';
						}else{
							echo '<li style="background:'.$row.'" data-color="'.$row.'"></li>';
						}
					}
				?>
			</ul>
		</div>
	</fieldset>
	<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
	var ue = UE.getEditor('ArticleContentModel_body',{
		autoHeightEnabled: false,
		initialFrameHeight:300
	});
	var url = "<?php echo $this->createUrl('DownloadCenter/BuildKeywords');?>";
	
	$(function(){
		$('.btn-reset').click(function(){
			ue.setContent('');
		});
		ue.addListener('blur',function(){
			var content = ue.getContentTxt();
			$.ajax({
				url: url,
				data: {'content': content},
				type:'POST',
				dataType: 'JSON',
				success: function(data){
					if(data.code == 0){
						$('#ArticleContentModel_keywords').val(data.result);
					}else{
						$('#ArticleContentModel_keywords').val('');
					}
				}
			});
		});

		var cbox  = $('#color-box');
		$('#ArticleContentModel_color').focus(function(){
			var pos = $(this).position();
			var posTop = pos.top+32+'px';
			var posLeft = pos.left+'px';
			cbox.css({'top':posTop, 'left':posLeft});
			cbox.show();
		});
		
		$('.color-none').click(function(){
			cbox.hide();
		})

		var lis = cbox.find('li');
		lis.each(function(){
			$(this).click(function(){
				var c = $(this).attr('data-color')==='#FFFFFF' ? '' : $(this).attr('data-color');
				$('#ArticleContentModel_color').val(c);
				cbox.hide();
			})
		});

		var preid = parseInt($('#ArticleContentModel_cid').find('option:selected').val());
		var ids = [24,25,26,27,59];
		if(preid && $.inArray(preid,ids) !== -1){
			$('#digital-box').show();
		}

		$('#ArticleContentModel_cid').change(function(){
			var cid = parseInt($(this).val());
			if(cid && $.inArray(cid,ids) !== -1){
				$('#digital-box').show();
			}else{
				$('#digital-box').hide();
			}
		});
	});
</script>