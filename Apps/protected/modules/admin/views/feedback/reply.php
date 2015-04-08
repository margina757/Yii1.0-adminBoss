<?php
$this->title = '评论回复 - ' . $this->titleTail;
?>
<style type="text/css">
	.table{
		width: 100%;
		border: 1px solid #ddd;
	}
	.table tr th{
		border: 1px solid #ddd;
	}
	.table tr{
		border: none;
	}
	.table tr td{
		border: none;
	}
</style>
<div class="containner">
	<legend>
		<h2>评论回复</h2>
	</legend>
	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
		));
	?>
	<fieldset>
	<table class="table">
		<thead>
			<tr>
				<th colspan="2">文章回复内容</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="span2">评论所属文章：</td>
				<td><?php echo $articleTitle;?></td>
			</tr>
			<tr>
				<td>评论者名称：</td>
				<td><?php echo $model->username;?></td>
			</tr>
			<tr>
				<td class="span2">评论发布时间：</td>
				<td><?php echo $model->created;?></td>
			</tr>
			<tr>
				<td class="span2">IP地址：</td>
				<td><?php echo $model->ip;?></td>
			</tr>
			<tr>
				<td class="span2">评论内容：</td>
				<td><?php echo $model->msg;?></td>
			</tr>
			<tr>
				<td class="span2">管理员回复：</td>
				<td><textarea name="reply" style="width:500px;height: 100px;"></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php echo TbHtml::formActions(array(
						TbHtml::submitButton('回复评论', array('color' => TbHtml::BUTTON_COLOR_PRIMARY,'size'=> TbHtml::BUTTON_SIZE_LARGE)),
					)); ?>
				</td>
			</tr>
		</tbody>
	</table>
	</fieldset>
	<?php $this->endWidget();?>
</div>