<?php
$this->title = '分类修改 - ' . $this->titleTail;
?>
<style>
	.hidden{
		display: none;
	}
	.radio{
		float: left;
		margin-right: 15px;
	}
</style>
<div class="rows">
	<div class="widget">
	<h2>栏目分类修改</h2>
	</div>
	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
		));
	?>
	<fieldset>
	
		<?php echo $form->textFieldControlGroup($model,'name',array('help' => '请填写栏目名称'));?>

		<?php echo $form->textFieldControlGroup($model,'sort',array('help'=>'（由低->高升序排列）','value'=>'50'));?>

		<?php //echo $form->dropDownListControlGroup($model,'reid',$listChannel,array('empty'=>'请选择分类所属频道'));?>
		<div class="control-group">
			<label class="control-label" for="ArticleChannelModel_reid">上级栏目</label>
			<div class="controls">
				<select name="ArticleChannelModel[reid]" id="ArticleChannelModel_reid">
				<?php echo $listChannel;?>
				</select>
			</div>
		</div>
		
		<?php echo $form->radioButtonListControlGroup($model,'ishidden',array(0 => '显示', 1 => '隐藏'));?>

	</fieldset>

	<?php echo TbHtml::formActions(array(
		TbHtml::submitButton('确定', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		TbHtml::resetButton('取消'),
	)); ?>
	<?php $this->endWidget(); ?>
</div>