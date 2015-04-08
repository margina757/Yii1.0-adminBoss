<?php
$this->title = '友情链接管理 - '.$this->titleTail;
?>

<div class="containner">
<fieldset>
<legend>
	<h2>友情链接列表</h2>
	<div class="rows">
		<a href="/admin/Flink/Add" class="btn">添加链接</a>
		<a href="javascript:void(0);" class="btn" id="select_all" data-status='1'>全选</a>
		<a href="javascript:void(0);" class="btn" id="ismake_all">批量审核</a>
		<a href="javascript:void(0);" class="btn" id="delete_all">批量删除</a>
	</div>
</legend>
	<?php 
	$data = $model->search();

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => TbHtml::GRID_TYPE_BORDERED,
		'dataProvider' => $data,
		'filter' => $model,
		'template' => "{items}\n{pager}",
		'selectableRows' => 2,
		'columns' => array(
			array(
				'class' => 'CCheckBoxColumn',
			),
			array(
				'header' => '站点LOGO',
				'name' => 'logo',
				'type' => 'html',
				'value' => 'FlinkModel::model()->getLogo("$data->logo")',
			),
			array(
				'header' => '站点名称 ',
				'name' => 'webName',
			),
			array(
				'header' => '站点链接',
				'name' => 'url',
			),
			array(
				'header' => '权重',
				'name' => 'sort',
			),
			array(
				'header' => '链接类型',
				'name' => 'type',
				'filter' => TbHtml::listData($flinkType,'flink','flink_value'),
				'value' => 'Yii::app()->params["flinkType"]["$data->type"]',
			),
			array(
				'header' => '是否审核',
				'name' => 'isCheck',
				'type' => 'html',
				'filter' => TbHtml::listData($isCheck,'status','status_check'),
				'value' => '$data->isCheck ? "<font color=#00ff00>已审核</font>" : "<font color=#ff0000>未审核</font>"',
			),
			array(
				'header' => '添加时间',
				'name' => 'created',
			),
			array(
				'header' => '修改时间',
				'name' => 'updated',
			),
			array(
				'class' => 'bootstrap.widgets.TbButtonColumn',
				'htmlOptions' => array('style'=>'width: 50px'),
				'template' => '{update} {delete}',
				'buttons'=>array(
					'update' => array(
						'label' => '编辑',
						'url' => 'Yii::app()->createUrl("admin/Flink/Update", array("id"=>$data->id))',
					),
					'delete' => array(
						'label' => '删除',
						'url' => 'Yii::app()->createUrl("admin/Flink/Delete", array("id"=>$data->id))',
						'click' => 'function(){ return confirm("是否确定删除此栏目？");}',
					),
				),
			),
		)
	));?>
</fieldset>
</div>
<script type="text/javascript">
	$(function(){
		$('#select_all').bind('click',function(){
			if($(this).attr('data-status') == 1){
				$('.select-on-check').each(function(){this.checked = true});
				$(this).attr('data-status',0);
				$(this).html('取消');
			}else{
				$('.select-on-check').each(function(){this.checked = false});
				$(this).attr('data-status',1);
				$(this).html('全选');
			}
		});

		$('#ismake_all').bind('click', function(){
			var arr = checkedData();
			if(arr.length == 0){
				alert('请选择需要审核的链接！');
			}else{
				var url = "<?php echo $this->createUrl('Flink/MutiIsCheck');?>";
				var sendData = {'idArr':arr};
				ajaxrequest(url, sendData);
			}
		});
		$('#delete_all').bind('click',function(){
			var arr = checkedData();
			if(arr.length == 0){
				alert('请选择需要删除的链接！');
			}else{
				var url = "<?php echo $this->createUrl('Flink/MutiDelete');?>";
				var sendData = {'idArr':arr};
				ajaxrequest(url, sendData);
			}
		});
		function ajaxrequest(url, sendData){
			$.ajax({
				url:url,
				data:sendData,
				type:'POST',
				dataType: 'JSON',
				success:function(data){
					if(data.code == 0){
						alert(data.msg);
						location.reload();
					}else{
						alert(data.msg);
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
	})
</script>