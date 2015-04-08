<?php
$this->title = '软件列表管理 - ' . $this->titleTail;
?>
<style type="text/css">
	.a_link{
		color: #666600;
		font-size: 13px;
		font-weight: bold;
		text-decoration: none;
	}
</style>
<div>
<fieldset>
<legend>
	<h2>软件列表管理</h2>
	<div class="rows">
		<a href="/admin/DownloadCenter/AddSoft" class="btn">添加软件</a>
		<a href="javascript:void(0);" class="btn" id="select_all" data-status='1'>全选</a>
		<a href="javascript:void(0);" class="btn" id="ismake_all">批量审核</a>
		<a href="javascript:void(0);" class="btn" id="delete_all">批量删除</a>
	</div>
</legend>
<?php 
	$data = $model->search($criteria);

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
				'header' => '缩略图',
				'type'=>'html',
				'value' => function($data){
			             	return CHtml::image(SoftInfoModel::model()->getLitpicByAid($data->aid) ? SoftInfoModel::model()->getLitpicByAid($data->aid) : Yii::app()->params['thumbnail']);
			             },
			             'htmlOptions' => array('style' =>'width:200px'),
			),
			array(
				'header' => '软件名称',
				'name' => 'title',
				'type'=>'html',
				'value' => 'SoftInfoModel::model()->getTitleByAid("$data->aid")',
			),
			array(
				'header' => '是否审核',
				'name' => 'ismark',
				'type' => 'raw',
				'filter' =>CHtml::listData($ismake,'status','status_mark'),
				'value' => 'SoftInfoModel::model()->getIsMake("$data->aid")',
			),
			array(
				'header' => '发布人',
				'name' => 'writer',
				'value' => 'SoftInfoModel::model()->getWriter("$data->aid")',
				'htmlOptions' => array('style' =>'width:80px'),
			),
			array(
				'header' => '文件类型',
				'name' => 'filetype',
				'filter' => CHtml::listData($filterData['filetype'],'filetype','filetype_value'),
			),
			array(
				'header' => '软件评分',
				'name' => 'rank',
				'filter' => CHtml::listData($filterData['softRank'],'softRank','softRank_value'),
			),
			array(
				'header' => '软件类型',
				'name' => 'softType',
				'filter' => CHtml::listData($filterData['softType'],'softType','softType_value'),
			),
			array(
				'header' => '界面语言',
				'name' => 'language',
				'filter' => CHtml::listData($filterData['language'],'language','language_value'),
			),
			array(
				'header' => '授权方式',
				'name' => 'accrEdit',
				'filter' => CHtml::listData($filterData['accredit'],'accredit','accredit_value'),
			),
			array(
				'header' => '下载次数',
				'name' => 'hits',
				'htmlOptions' => array('style' =>'width:80px'),
			),
			array(
				'class' => 'bootstrap.widgets.TbButtonColumn',
				'template' => '{update} {delete}',
				'buttons'=>array(
					'update' => array(
						'label'=>'编辑',
						'url'=>'Yii::app()->createUrl("admin/DownloadCenter/UpdateSoft", array("aid"=>$data->aid))',
					),
					'delete' => array(
						'label'=>'删除',
						'url'=>'Yii::app()->createUrl("admin/DownloadCenter/DeleteSoft", array("aid"=>$data->aid))',
						'click' => 'function(){ return confirm("是否确定删除此栏目？");}',	
					),
				),
			),
		)
	));
?>
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
				alert('请选择需要审核的文章！');
			}else{
				var url = "<?php echo $this->createUrl('DownloadCenter/MutiIsmake');?>";
				var sendData = {'idArr':arr};
				ajaxrequest(url, sendData);
			}
		});
		$('#delete_all').bind('click',function(){
			var arr = checkedData();
			if(arr.length == 0){
				alert('请选择需要删除的软件！');
			}else{
				var url = "<?php echo $this->createUrl('DownloadCenter/MutiDelete');?>";
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