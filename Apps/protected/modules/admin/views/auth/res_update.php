
<?php
$this->title = '更新资源 - ' . $this->titleTail;
?>
<div>
    <div class="widget">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,));
        ?>
        <fieldset>
            <h2>更新资源信息</h2>
            <?php
            // echo TbHtml::quote('更新资源信息');
            ?>

            <?php echo $form->textFieldControlGroup($model, 'controller',
                array('help' => '请填写新的控制器名称','label' => '控制器名称')); ?>

            <?php echo $form->textFieldControlGroup($model, 'action',
                array('help' => '请填写新的动作名称','label' => '动作名称')); ?>

            <?php echo $form->textFieldControlGroup($model, 'description',
                array('help' => '请填写资源描述','label' => '资源描述')); ?>
        </fieldset>
        <?php echo TbHtml::formActions(array(
            TbHtml::submitButton('提交', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE)),
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>

