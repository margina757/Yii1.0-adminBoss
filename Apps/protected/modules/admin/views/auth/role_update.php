<?php
$this->title = '添加用户组 - ' . $this->titleTail;
?>
<script type="text/javascript" src="/js/jquery.js"></script>
<div class="containner">
    <div class="widget">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,));
        //2014-5-14 liuxuegang
        $hanhua = Yii::app()->params['hanhua'];
        ?>
        <fieldset>
            <h2><?php echo $title ? $title :"管理授权";?></h2>
            <?php echo $form->textFieldControlGroup($roleModel, 'name',
                array('help' => '请填写用户组名称','label' => '用户组名称')); ?>
            <?php echo $form->hiddenField($roleModel, 'role'); ?>
            <?php
            if (@!$addRole) {
            echo "<div class='control-group'>
                    <label class='control-label' for='ResourcesAuthModel_rid'>全选</label>
                    <div class='controls'>
                        <span id='ResourcesAuthModel_rid'>
                        <label class='checkbox'> <input id='checkAll' type='checkbox' name='checkAll'><span style='color:green;'>全选</span></label>
                </span>
                    </div>
                </div>";

                foreach ($resAuth as $group => $item) {
                    //***********************************************************************//
                    $hanhuaGroup = $hanhua[$group];
                    if(!$hanhuaGroup){
                        $hanhuaGroup=$group;
                    }
            echo "<div class='control-group'>
                    <label class='control-label' for='ResourcesAuthModel_rid'>{$hanhuaGroup}模块</label>
                    <div class='controls'>
                        <span id='ResourcesAuthModel_rid'>";
                        $checkAllDefault = "checked='checked'";
                        foreach ($item as $k => $v) {
                            $checkDefault = in_array($k, $model->rid) ? "checked='checked'"  : "";
                            echo" <label class='inline checkbox'> <input group='{$group}' value='{$k}' {$checkDefault} type='checkbox' name='ResourcesAuthModel[rid][]'> {$v}</label>";
                            if (!$checkDefault) {
                                $checkAllDefault = $checkDefault;
                            }
                        }
                        echo" <label class='checkbox'> <input id='{$group}' value='0' onclick='checkedGroup(this)' {$checkAllDefault} type='checkbox' name='checkAll_{$group}'><span style='color:green;'>全选</span></label>";
                echo "</span>
                    </div>
                </div>";
                }
            }
            ?>
        </fieldset>

        <?php echo TbHtml::formActions(array(
            TbHtml::submitButton('提交', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'size' => TbHtml::BUTTON_SIZE_LARGE)),
        )); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script>
    function checkedGroup(obj) {
        var group = obj.id;
        var targetStatus = jQuery('#'+group).attr('checked');
        jQuery("input[group=" + group + "]").each(function() {this.checked = targetStatus});
    }

    jQuery("input[type='checkbox']").click(function() {
        var group = jQuery(this).attr('group');
        var totalCount = jQuery("input[group="+group+"]").length;
        var checkedCount = jQuery("input[group=" + group + "]:checked").length;
        if (!group)
            return
        if (totalCount == checkedCount) {
            jQuery('#' + group).attr('checked', 'checked');
        } else {
            jQuery('#' + group).attr('checked', false);
        }
    });

    jQuery("#checkAll").click(function(){
        var targetStatus = jQuery(this).attr('checked');
        jQuery("input[type='checkbox']").each(function() {this.checked = targetStatus});
    })

</script>