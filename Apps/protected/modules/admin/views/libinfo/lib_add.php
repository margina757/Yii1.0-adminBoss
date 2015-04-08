<?php
$this->title = '图书馆概况 - '.$this->titleTail;
?>

<div class="containner">
	<legend>
	<?php if(isset($id)){
		echo "<h2>图书馆概况栏目修改</h2>";
	}else{
		echo "<h2>图书馆概况栏目添加</h2>";
	}?>
	</legend>

	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
			'htmlOptions' => array('enctype'=>'multipart/form-data'),
		));
	?>
	<fieldset>
		<?php echo $form->textFieldControlGroup($model,'name',array('help' => '此处本馆概况栏目下的左侧的子栏目名'));?>
		
		<?php echo $form->textFieldControlGroup($model,'title',array('help' => '栏目相关介绍标题'));?>

		<?php echo $form->textFieldControlGroup($model,'sort',array('help' => '权重默认为50，越小越靠前'));?>

		<?php echo $form->textAreaControlGroup($model,'body',array(
			'id'=>'LibInfoModel_body',
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
	var ue = UE.getEditor('LibInfoModel_body');
	$(function(){
		$('.btn-reset').click(function(){
			ue.setContent('');
		});
	});
</script>