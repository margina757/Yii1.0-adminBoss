<?php
$this->title = '用户信息 - '.$this->titleTail;
?>
<div class="containner">
	<legend>
		<h2><?php echo $title ? $title : '创建管理员'?></h2>
	</legend>
	<?php   $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
		));
	?>
	<fieldset>
		<?php
			if (!$isUpdate) {
				echo $form->textFieldControlGroup($model,'username',
					array('help' => '请填写管理员名称','label'=>'管理员名称'));
			 }else{
			 	echo $form->textFieldControlGroup($model,'username',
			 	array('help' => '请填写管理员名称','label'=>'管理员名称','disabled' => 'disabled'));
			}
		?>

		<?php echo $form->hiddenField($model, 'uid'); ?>

		<?php
			if (!$isUpdate) {
				echo $form->passwordFieldControlGroup($model, 'password',
				array('help' => '请填写管理员密码','label' => '管理员密码'));
			}else{
				unset($model->password);
				echo $form->passwordFieldControlGroup($model, 'password',
				array('help' => '请填写新密码,如不修改,请留空!','label' => '管理员密码'));
			}
		?>

		<?php echo $form->dropDownListControlGroup($model,'role',
			$listAllRole,
			array('empty'=>'请选择用户组','label'=>'所属用户组'));?>

	</fieldset>
	<?php echo TbHtml::formActions(array(
		TbHtml::submitButton('确定', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		TbHtml::resetButton('重置',array('class'=>'btn-reset')),
	)); ?>

	<?php $this->endWidget(); ?>
</div>
