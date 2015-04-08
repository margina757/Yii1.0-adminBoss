<?php
$this->title = '资源文章权重管理 - '.$this->titleTail;
?>

<style type="text/css">
a.title{
	margin-left:20px;
	display: inline-block;
	text-decoration: none;
	font-size: 14px;
	padding: 5px 10px;
}
a.active{
	color: #304269;
}
a.disable{
	color: #666;
}
</style>

<div class="containner">
<fieldset>
<legend>
	<h2>资源文章权重管理</h2>
	<div class="rows">
		<?php $cid = $_GET['cid'] ? $_GET['cid'] : 24;?>
		<a href='/admin/resource/index/cid/24' class='title <?php echo $cid==24 ? 'active' : 'disable';?>'><?php echo $ResourceArr[24];?></a>
		<a href='/admin/resource/index/cid/25' class='title <?php echo $cid==25 ? 'active' : 'disable';?>'><?php echo $ResourceArr[25];?></a>
		<a href='/admin/resource/index/cid/26' class='title <?php echo $cid==26 ? 'active' : 'disable';?>'><?php echo $ResourceArr[26];?></a>
		<a href='/admin/resource/index/cid/27' class='title <?php echo $cid==27 ? 'active' : 'disable';?>'><?php echo $ResourceArr[27];?></a>
	</div>
</legend>
	<?php 
	$data = $model->search(array('cid'=>$cid));

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => TbHtml::GRID_TYPE_BORDERED,
		'dataProvider' => $data,
		// 'filter' => $model,
		'template' => "{items}\n{pager}",
		'selectableRows' => 2,
		'columns' => array(
			array(
				'class' => 'CCheckBoxColumn',
			),
			array(
				'name' => 'title',
				'htmlOptions' => array('style' => 'width:50%;'),
			),
			array(
				'header' => '文章权重(越小越靠前)',
				'type' => 'raw',
				'name' => 'sort',
				'value' => function($data){
			             		return CHtml::textField('sort',$data->sort,array('class' => 'span1'));
			             }
			),
		)
	));?>
	<a href="javascript:;" class="btn btn-primary" style="margin-bottom:20px;" id="update-sort">更新权重</a>
</fieldset>
</div>
<script type="text/javascript">
	$(function(){
		$('#update-sort').bind('click',function(){
			var data = checkedData();

			//文章aid
			var aids = data['aids'];
			//对应aid的权重值
			var sort = data['sort'];

			if(aids.length == 0){
				alert('请选择需要更新权重的项！');
			}else{
				var url = "<?php echo $this->createUrl('resource/updateSort');?>";
				var sendData = {'aids':aids,'sort':sort};
				console.log(sendData);
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
			var aids = [], 
			       sort = [],
			       i=0,
			       data = [];
			$("input[class=select-on-check]:checked").each(function(){
				sort[i] = $(this).parent().parent().find('input[id=sort]').val();
				aids[i] = $(this).val();
				i++;
			});
			data['aids'] = aids;
			data['sort'] = sort;
			return data;
		}
	})
</script>