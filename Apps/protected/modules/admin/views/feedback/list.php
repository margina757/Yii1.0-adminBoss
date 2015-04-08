<?php
$this->title = '评论列表管理 - ' . $this->titleTail;
?>
<style type="text/css">
    .caozuo{
        margin-bottom: 10px;

    }
    .caozuo button{width: 50px;margin-right: 5px;}
    </style>
<div>
<fieldset>
	<legend>
		<h2>评论管理</h2>
		<div class="caozuo">
	        <button id="delete_all">删除</button>
	        <button id="ismake_all">审核</button>
    	</div> 
	</legend>

<?php
	$data = $model->search();
	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => TbHtml::GRID_TYPE_BORDERED,
		'dataProvider' => $data,
		'filter' => $model,
		'selectableRows' => 2,
		'template' => "{items}\n{pager}",
		'columns' => array(
			array(
				'header' => 'ID',
				'name' => 'id',
			),
			array(

                'class' => 'CCheckBoxColumn',
            ),
            array(
            	'header' => '评论用户名',
            	'name' => 'username',
            ),
            array(
            	'header' => '文章标题',
            	'name' => 'title',
               'type'=>'html',
               'value' =>  function($data){
                            echo  CHtml::link($data->feedback->title, '/news/'.$data->aid, array('title' => $data->feedback->title, 'target'=>'_blank'));
                        },
            ),
            array(
            	'header' => '评论内容',
              'type' => 'raw',
            	'name' => 'msg',
            ),
            array(
            	'header' => '是否审核',
            	'name' => 'isCheck',
            	'type' => 'raw',
            	'filter' => CHtml::listData($ischeck, 'isCheck', 'ischeck_status'),
            	'htmlOptions' => array('style' => 'width:160px'),
            	'value' => 'FeedbackModel::model()->feedbackIscheck("$data->id")',
            ),
            array(
            	'header' => '支持数',
            	'name' => 'good',
            ),
            array(
            	'header' => '反对数',
            	'name' => 'bad',
            ),
            array(
            	'header' => '评论时间',
            	'name' => 'created',
            ),
            array(
            	'header' => '操作',
            	'class' => 'bootstrap.widgets.TbButtonColumn',
            	'template' => '{update} {delete}',
            	'buttons' => array(
                              'update' => array(
                                            'label' => '回复留言',
                                            'url' => 'Yii::app()->createUrl("admin/Feedback/Reply", array("id"=>$data->id))',
                              ),
            		'delete' => array(
            			'label' => '删除',
            			'url' => 'Yii::app()->createUrl("admin/Feedback/Delete", array("id"=>$data->id))',
            		),
            	),
            ),
		),
	));


?>
</fieldset>


</div>
<script>
function ajaxrequest(url, sendData){
    $.ajax({
        url:url,
        data:sendData,
        type:'POST',
        success:function(data){
           if(data){
                alert('操作成功');
                location.reload();
           }else{
                alert('操作失败');
           }
        }
    })
}

function checkedData(){
    var arr = [];    
    var i=0;
    $("input:checked").each(function(){         
        arr[i] = $(this).val();
        i++;
    });
    return arr;
}

$(function(){
    $('#delete_all').bind({
        click:function(){
            var arr = checkedData();
            if(arr.length == 0){
                alert('请选择删除的评论！');
            }else{
                var url = "<?php echo $this->createUrl('Feedback/Delete');?>";
                var sendData = {'idArr':arr};
                ajaxrequest(url, sendData);
            }

        },
    })

    $('#ismake_all').bind({
        click:function(){
            var arr = checkedData();
            if(arr.length == 0){
                alert('请选择审核的评论！');
            }else{
                var url = "<?php echo $this->createUrl('Feedback/FeedbackIscheck');?>";
                var sendData = {'idArr':arr};
                ajaxrequest(url, sendData);
            }

        },
    })
})
</script>