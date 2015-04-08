<?php
$this->title = '创建资源 - ' . $this->titleTail;
?>
<div>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,));
?>
<fieldset>
    <legend><h2>创建资源</h2></legend>
    <?php echo $form->textFieldControlGroup($model, 'controller',
        array('help' => '请填写控制器名称 例如 node','label' => '控制器名称')); ?>

    <?php echo $form->textFieldControlGroup($model, 'action',
        array('help' => '请填写动作名称 例如 new','label' => '动作名称')); ?>

    <?php echo $form->textFieldControlGroup($model, 'description',
        array('help' => '请填写操作描述 例如 创建文章','label' => '操作描述')); ?>
</fieldset>
<?php echo TbHtml::formActions(array(
    TbHtml::submitButton('提交', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE)),
)); ?>
<?php $this->endWidget(); ?>
</div>
