
<?php
$this->title = '管理用户组 - ' . $this->titleTail;
?>
<div class="rows">
    <div class="widget">
        <h2>管理用户组(<a href='/admin/auth/addRole'>+添加用户组</a>)</h2>
        <?php
        $data = $model->search();
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => TbHtml::GRID_TYPE_BORDERED,
            'dataProvider' => $data,
        //    'filter' => $model,
            'template' => "{items}\n{pager}",
            'columns' => array(
                array(
                    'header'      => '用户组',
                    'name'        => 'name',
                ),
                array(
                    'class'       => 'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update} {delete}',
                    'buttons'=>array
                    (
                        'update' => array
                        (
                            'label'=>'编辑',
                            'url'=>'Yii::app()->createUrl("/admin/auth/updateRole", array("id"=>$data->role))',
                        ),
                        'delete' => array(
                            'label' => '删除',
                            'url'=>'Yii::app()->createUrl("/admin/auth/delRole",array("id"=>$data->role))',
                            'click' => 'function(){ return confirm("是否确定删除此用户组？");}',
                        ),
                    ),
                ),
            )
        )); ?>
    </div>
</div>
