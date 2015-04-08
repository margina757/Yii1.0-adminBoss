<?php
$this->title = '友情链接管理 - '.$this->titleTail;
?>

<div class="containner">
	<legend>
	<?php if(isset($id)){
		echo "<h2>友情链接管理修改</h2>";
	}else{
		echo "<h2>友情链接管理添加</h2>";
	}?>
	</legend>

	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
			'htmlOptions' => array('enctype'=>'multipart/form-data'),
		));
	?>
	<fieldset>
		<?php echo $form->textFieldControlGroup($model,'webName',array('help' => '请输入对应站点的名称'));?>
		
		<?php echo $form->textFieldControlGroup($model,'url');?>

		<?php echo $form->dropDownListControlGroup($model,'type',Yii::app()->params['flinkType']);?>

		<?php echo $form->fileFieldControlGroup($model, 'logo'); ?>

		<?php echo $form->textFieldControlGroup($model,'sort',array('help' => '权重默认为50，越小越靠前'));?>
		
		<?php //echo $form->dropDownListControlGroup($model,'isCheck',array(0=>'未审核',1=>'审核'),array('label' => '审核状态'));?>		

		<?php echo $form->textAreaControlGroup($model,'info',array(
			'id'=>'FlinkModel_info',
			'style' => 'width:100%;height:300px;',
		));?>

	</fieldset>
	<?php echo TbHtml::formActions(array(
		TbHtml::submitButton('确定', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		TbHtml::resetButton('重置',array('class'=>'btn-reset')),
	)); ?>

	<?php $this->endWidget(); ?>

</div>

<script type="text/javascript">
	var ue = UE.getEditor('FlinkModel_info');
	$(function(){
		$('.btn-reset').click(function(){
			ue.setContent('');
		});
	});
</script>