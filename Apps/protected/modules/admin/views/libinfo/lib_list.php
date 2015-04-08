<?php
$this->title = '图书馆概况 - '.$this->titleTail;
?>

<div class="containner">
<fieldset>
<legend>
	<h2>图书馆概况栏目列表</h2>
</legend>
	<?php 
	$data = $model->search();

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => TbHtml::GRID_TYPE_BORDERED,
		'dataProvider' => $data,
		'filter' => $model,
		'template' => "{items}\n{pager}",
		// 'selectableRows' => 2,
		'columns' => array(
			// array(
			// 	'class' => 'CCheckBoxColumn',
			// ),
			array(
				'header' => '子栏目名',
				'name' => 'name',
			),
			array(
				'header' => '内容介绍标题 ',
				'name' => 'title',
			),
			array(
				'header' => '权重',
				'name' => 'sort',
			),
			array(
				'header' => '发布人',
				'name' => 'writer',
			),
			array(
				'header' => '点击量',
				'name' => 'click',
			),
			array(
				'header' => '发布时间',
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
						'url' => 'Yii::app()->createUrl("admin/LibInfo/UpdateLibInfo", array("id"=>$data->id))',
					),
					'delete' => array(
						'label' => '删除',
						'url' => 'Yii::app()->createUrl("admin/LibInfo/DelLibInfo", array("id"=>$data->id))',
						'click' => 'function(){ return confirm("是否确定删除此栏目？");}',
					),
				),
			),
		)
	));?>
</fieldset>
</div>