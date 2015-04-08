<?php
$this->title = '文章列表管理 - ' . $this->titleTail;
?>
<style type="text/css">
    /* .delete_all,.ismake_all{cursor: pointer;} */
    .caozuo{
        margin-bottom: 10px;

    }
    .caozuo button{width: 50px;margin-right: 5px;}
    </style>
<div>
<fieldset>
<legend>
    <h2>文章列表管理</h2>
   <!--  <div style="margin-bottom:10px;">
       <span id="select_all">全选</span> &nbsp;&nbsp;&nbsp;&nbsp;<span id="select_none">取消</span>
   </div>  -->
    <div class="caozuo">
       <!--  <button id="select_all">全选</button>
       <button id="select_none">取消</button> -->
        <button id="delete_all">删除</button>
        <button id="ismake_all">审核</button>
    </div> 
</legend>

<?php 
    $data = $model->search();
    //$page = $data->getPagination();
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => TbHtml::GRID_TYPE_BORDERED,
        'dataProvider' => $data,
        'filter' => $model,
        'selectableRows' => 2,
        'template' => "{items}\n{pager}",
        'columns' => array(
            array(
                'header' => 'ID',
                'name' => 'aid',
            ),
            array(

                'class' => 'CCheckBoxColumn',
            ),
            array(
                'header' => '文章标题',                  
                'name' => 'title',
                'type'=>'html',
                'value' =>  function($data){
                            echo  CHtml::link($data->title, '/news/'.$data->aid, array('title' => $data->title, 'target'=>'_blank'));
                         },

            ),
            array(
                'header' => '文章内容',
                'name' => 'body',
                'value' => 'StrCut::cutOut($data->body,40)', 
            ),
            array(
                'header' => '缩略图',
                'name' => 'litpic',
                'type' => 'html',
                'value' => function($data){
                            return CHtml::image($data->litpic ? $data->litpic : Yii::app()->params['thumbnail'],'',array('style'=>'width:97px;'));
                         },
            ),
             array(
                'header' => '标签',
                'name' => 'flag',
            ),
            array(
                'header' => '栏目',
                'name' => 'cid', 
                //'type' => 'raw',
                'filter' => CHtml::listData($channelList, 'id', 'name'),
                'htmlOptions' => array('style' => 'width:160px'),
                'value' => 'ArticleContentModel::model()->getArticleChannel("$data->cid")'
            ),
            array(
                'header' => '是否审核',
                'name' => 'ismake',
                'type' => 'raw',
                'filter' =>CHtml::listData($ismake,'ismake','status_mark'),
                'htmlOptions' => array('style' => 'width:90px'),
                'value' => 'ArticleContentModel::model()->judgeIsmake("$data->aid")',
            ),
             array(
                'header' => '点击数',
                'name' => 'click',
            ),
            array(
                'header' => '创建时间',
                'name' => 'created',
            ),
            array(
                'header' => '操作',
                'class' => 'bootstrap.widgets.TbButtonColumn',
                //'htmlOptions' => array('style'=>'width: 50px'),
                'template' => '{update} {delete}',
                'buttons'=>array(
                    'update' => array(
                        'label'=>'编辑',
                        'url'=>'Yii::app()->createUrl("admin/ArticleContent/UpdateArticle", array("id"=>$data->aid))',
                    ),
                    'delete' => array(
                        'label'=>'删除',
                        'url'=>'Yii::app()->createUrl("admin/ArticleContent/Delete", array("id"=>$data->aid))',    
                    ),
                ),
            ),

        )
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
        dataType:'JSON',
        success:function(data){
           if(data.code==1){
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

$(function(){
    $('#delete_all').bind({
        click:function(){
            var arr = checkedData();
            if(arr.length == 0){
                alert('请选择删除的文章！');
            }else{
                var url = "<?php echo $this->createUrl('ArticleContent/BatchDelete');?>";
                var sendData = {'idArr':arr};
                ajaxrequest(url, sendData);
            }

        },
    })

    $('#ismake_all').bind({
        click:function(){
            var arr = checkedData();
            if(arr.length == 0){
                alert('请选择审核的文章！');
            }else{
                var url = "<?php echo $this->createUrl('ArticleContent/ArticleIsmake');?>";
                var sendData = {'idArr':arr};
                ajaxrequest(url, sendData);
            }

        },
    })
})
</script>