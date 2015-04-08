<style>
.add_res{color:#0c3ad5;}
</style>
<?php
$this->title = '管理资源 - ' . $this->titleTail;
?>
<div class="rows">
    <div class="widget">
        <fieldset>
        <h2>管理资源列表(<a class='add_res' href='/admin/auth/addRes'>+添加资源</a>)</h2>
        <?php
        $data = $model->search();
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => TbHtml::GRID_TYPE_BORDERED,
            'dataProvider' => $data,
            'filter' => $model,
            'template' => "{items}\n{pager}",
            'columns' => array(
                array(
                    'header' => '资源标识',
                    'name'   => 'rid',
                    'htmlOptions' => array('class' => 'input-small')
                ),
                array(
                    'header'      => '控制器名称',
                    'name'        => 'controller',
                ),
                array(
                    'header'      => '动作名称',
                    'name'        => 'action',
                ),
                array(
                    'header'      => '资源描述',
                    'name'        => 'description',
                ),
                array(
                    'class'       => 'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array
                    (
                        'update' => array
                        (
                            'label'=>'编辑',
                            'url'=>'Yii::app()->createUrl("/admin/auth/updateRes", array("id"=>$data->rid))',
                        ),
                    ),
                ),
            )
        )); ?>
        </fieldset>
    </div>
</div>
