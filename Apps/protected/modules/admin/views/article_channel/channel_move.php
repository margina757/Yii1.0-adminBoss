<?php
$this->title = '移动栏目 - ' . $this->titleTail;
?>
<style type="text/css">
	.description{
		width: 240px;
	}
</style>
<div class="containner">
<h2>移动栏目</h2>
<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	));
?>
<fieldset>
<table class="items table table-striped">
	<thead>
	<tr>	
		<th style="color:#666600;" colspan="2">移动目录时不会删除原来已创建的列表</th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td class="description">你选择的栏目是：</td>
			<td><?php echo $channelInfo['name'].'('.$channelInfo['id'].')';?></td>
		</tr>
		<tr>
			<td>你希望移动到那个栏目？</td>
			<td>
				<select name="ArticleChannelModel[id]" id="ArticleChannelModel_id">
				<?php
					echo $menuList;
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>注意事项：</td>
			<td>不允许从父级移动到子级目录，只允许子级到更高级或同级或不同父级的情况。</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="form-actions">
					<button class="btn btn-primary" type="submit" name="yt0">确定</button> 
					<button class="btn" type="reset" name="yt1">重置</button>
					<a class="btn" href="javascript:viod(0);" onclick="history.go(-1);">返回</a>
				</div>
			</td>
		</tr>
	</tbody>
</table>
</fieldset>
<?php $this->endWidget(); ?>
</div>