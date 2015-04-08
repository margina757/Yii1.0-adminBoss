<?php
$this->title = '软件信息修改 - ' . $this->titleTail;
?>
<style type="text/css">
	.soft-content {
		border: 1px solid #d2d2d2;
		width: 100%;
		overflow: hidden;
	}
	.soft-content li {
		border-bottom: 1px dashed #d2d2d2;
		overflow: hidden;
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
		<h2>软件信息修改</h2>
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
			<li>
				<?php echo $form->textFieldControlGroup($articleContentModel,'title',array(
					'size' => TbHtml::INPUT_SIZE_XLARGE,
					'label'=>'软件名称：',
				));?>

				<?php echo $form->textFieldControlGroup($articleContentModel,'shorttitle',array(
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
				<?php echo $form->fileFieldControlGroup($articleContentModel, 'litpic',array(
					'label'=>'缩略图：',
				)); ?>
			</li>

			<li>
				<?php echo $form->textFieldControlGroup($articleContentModel,'writer',array(
					'label'=>'软件作者：',
				));?>
			</li>

			<li>
				<?php echo $form->dropDownListControlGroup($softInfoModel,'filetype',Yii::app()->params['filetype'],array(
					'empty'=>'请选择文件类型',
					'label'=>' 文件类型：',
				));?>

				<?php echo $form->dropDownListControlGroup($softInfoModel,'rank',Yii::app()->params['softRank'],array(
					'empty'=>'请选择软件评分',
					'label'=>' 软件评分：',
				));?>
			</li>

			<li>
				<?php echo $form->dropDownListControlGroup($softInfoModel,'softType',Yii::app()->params['softType'],array(
					'empty'=>'请选择软件类型',
					'label'=>' 软件类型：',
					'size'=>TbHtml::INPUT_SIZE_MEDIUM,
				));?>

				<?php echo $form->dropDownListControlGroup($softInfoModel,'language',Yii::app()->params['language'],array(
					'empty'=>'请选择界面语言',
					'label'=>' 界面语言：',
					'size'=>TbHtml::INPUT_SIZE_MEDIUM,
				));?>
			</li>

			<li>
				<?php echo $form->dropDownListControlGroup($softInfoModel,'accrEdit',Yii::app()->params['accredit'],array(
					'empty'=>'请选择授权方式',
					'label'=>' 授权方式：',
					'size'=>TbHtml::INPUT_SIZE_MEDIUM,
				));?>

				<?php echo $form->textFieldControlGroup($softInfoModel,'os',array(
					'size' => TbHtml::INPUT_SIZE_XLARGE,
					'label'=>'运行环境：',
					'value' => 'Win2003,WinXP,Win2000,Win9X'
				));?>
			</li>

			<li>
				<?php echo $form->textFieldControlGroup($softInfoModel,'officialUrl',array(
					'label'=>' 官方网址：',
				));?>

				<?php echo $form->textFieldControlGroup($softInfoModel,'officialDemo',array(
					'label'=>' 演示地址：',
				));?>
			</li>

			<li>
				<?php echo $form->textFieldControlGroup($softInfoModel,'softSize',array(
					'label'=>' 软件大小：',
					'value' => 'MB',
				));?>

				<?php echo $form->fileFieldControlGroup($softInfoModel,'link',array(
					'label'=>' 上传软件：',
				));?>

			</li>

			<li>
				<?php echo $form->inlineradioButtonListControlGroup($articleContentModel,'notpost',array(
					0=>'允许',
					1=>'不允许'
				),array(
					'label' =>'是否允许评论：',
				));?>

				<?php echo $form->textFieldControlGroup($articleContentModel,'color',array(
					'size' => TbHtml::INPUT_SIZE_LARGE,
					'label'=>' 标题颜色：',
				));?>
			</li>

			<li>
				<?php echo $form->dropDownListControlGroup($articleContentModel,'ismake',array(
					1 => '未审核',
					2 => '已审核',
				),array(
					'label'=>' 阅读权限：',
					'size'=>TbHtml::INPUT_SIZE_MEDIUM,
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
				<?php echo $form->textFieldControlGroup($articleContentModel,'keywords',array(
					'size' => TbHtml::INPUT_SIZE_XLARGE,
					'label'=>' 关键词：',
					'help' => '不填写程序自动提取5个关键词，手动填写用","分开',
				));?>
			</li>

			<li>
				<?php echo $form->textAreaControlGroup($articleContentModel, 'description',array(
					'span' => 8,
					'rows' => 5,
					'label' => '内容摘要：',
				)); ?>
			</li>

			<li>
				<?php echo $form->textAreaControlGroup($articleContentModel,'body',array(
					'id'=>'ArticleContentModel_body',
					'label' => '软件详细介绍：',
					'style' => 'width:98%;height:300px;',
				));?>
			</li>

			<li>
				<?php echo TbHtml::formActions(array(
					TbHtml::submitButton('确定', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
					TbHtml::resetButton('重置'),
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
	$(function(){
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
	});
</script>