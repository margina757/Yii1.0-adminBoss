<style>
.add_user{color:#0c3ad5;}
</style>

<?php
$this->title = '管理员列表 - ' . $this->titleTail;
?>
<div class="rows">
    <div class="widget">
        <fieldset>
        <h2>管理员列表(<a href="/admin/auth/Add" class="add_user">+添加管理员</a>)</h2>
        <?php
        $data = $model->search();

        if(@!$data){
            $strTmp = "<table><tr><td>无数据</td></tr></table>";
        }else{

            $this->widget('bootstrap.widgets.TbGridView', array(
                'type' => TbHtml::GRID_TYPE_BORDERED,
                'dataProvider' => $data,
                'filter' => $model,
                'template' => "{items}\n{pager}",
                'columns' => array(
                    array(
                        'header' => '用户标识',
                        'name'   => 'uid',
                        'htmlOptions' => array('class' => 'input-small')
                    ),
                    array(
                        'header'      => '管理员名称',
                        'name'        => 'username',
                    ),
                    array(
                        'header'      => '角色',
                        'name'        => 'roleName',
                        'value'       => 'AdminUserModel::getRoleName($data->role)',
                    ),
                    array(
                        'class'       => 'bootstrap.widgets.TbButtonColumn',
                        'template'=>'{update} {delete}',
                        'buttons'=>array(
                            'update' => array(
                                'label' => '编辑',
                            ),
                            'delete' => array(
                                'label' => '删除',
                                'click' => 'function(){ return confirm("是否确定删除此栏目？");}',
                            ),
                        ),
                    ),
                )
            )); 
        }//else end 
    ?>
        </fieldset>
    </div>
</div>

