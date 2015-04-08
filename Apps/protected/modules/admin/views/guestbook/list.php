<?php
$this->title = '留言列表管理 - ' . $this->titleTail;
?>
<div>
	<fieldset>
		<legend>
			<h2>留言列表管理</h2>
			<div class="rows">
				<a href="javascript:void(0);" class="btn" id="ismake_all">批量审核</a>
				<a href="javascript:void(0);" class="btn" id="delete_all">批量删除</a>
			</div>
		</legend>
	

	<?php
	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => TbHtml::GRID_TYPE_BORDERED,
		'dataProvider' => $data,
		'filter' => $model,
		'selectableRows' => 2,
		'template' => "{items}\n{pager}",
		'pager' => array(
			'header' => '',
			'firstPageLabel' => '首页',
			'lastPageLabel'=>'尾页',
        	'nextPageLabel'=>'下一页',
        	'prevPageLabel'=>'上一页',
		),
		'columns' => array(
			
			array(
				'header' => 'ID',
				'name' => 'id',
			),
			array(
                'class' => 'CCheckBoxColumn',
            ),
			array(
				'header' => '主题',
				'name' => 'title',	
			),
			array(
				'header' => '状态',
				'name' => 'ischeck',
				'filter' => TbHtml::listData($isCheck,'status','status_check'),
				'value' => '$data->ischeck==1 ? "未审核" : "已审核"',
			),
			array(
				'header' => '留言内容',
				'name' => 'msg',
				'value' => 'StrCut::cutOut($data->msg,40)',
			),
			array(
				'header' => '留言人姓名',
				'name' => 'uname',	
			),
			array(
				'header' => '邮箱',
				'name' => 'email',
			),
			array(
				'header' => 'QQ',
				'name' => 'qq',
			),
			
			array(
				'header' => 'IP',
				'name' => 'ip',
			),
			array(
				'header' => '发布时间',
				'name' => 'dtime',
				'value' => 'date("Y-m-d H:i:s",$data->dtime)',
			),
			array(
				'header' => '操作',
				'class' => 'bootstrap.widgets.TbButtonColumn',
				'template' => '{update} {delete}',
				'buttons' =>array(
					'update' => array(
						'label' => '回复',
						'url' => 'Yii::app()->createUrl("admin/Guestbook/Replay",array("id"=>$data->id))',
					),
					'delete' => array(
						'label' => '删除',
						'url' => 'Yii::app()->createUrl("admin/Guestbook/Delete",array("id"=>$data->id))',
					),
				),
			),
		),

	));
	?>
	</fieldset>
</div>

<script type="text/javascript">
	$(function(){

		$('#ismake_all').bind('click', function(){
			var arr = checkedData();
			console.log(arr);
			if(arr.length == 0){
				alert('请选择需要审核的留言！');
			}else{
				var url = "<?php echo $this->createUrl('Guestbook/MutiIsCheck');?>";
				var sendData = {'idArr':arr};
				ajaxrequest(url, sendData);
			}
		});
		$('#delete_all').bind('click',function(){
			var arr = checkedData();
			if(arr.length == 0){
				alert('请选择需要删除的留言！');
			}else{
				var url = "<?php echo $this->createUrl('Guestbook/MutiDelete');?>";
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