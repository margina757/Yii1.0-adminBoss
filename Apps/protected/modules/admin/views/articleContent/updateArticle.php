<link rel="stylesheet" href="/css/datepicker.css">
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/ueditor/ueditor.config.js');?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/ueditor/ueditor.all.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/ueditor/lang/zh-cn/zh-cn.js');?>
<style>
.errorMessage{   display:inline-block;float:left;color:red;font-size:12px;}
.litpic{height: 100px;border-bottom: 1px dashed #BCBCBC; padding-bottom: 3px;}
.litpic li{float: left;}
.bqname{margin-top: 40px;margin-right: 203px;}
.fileinput{margin-top: 35px;margin-right: 50px;}
.zy{height: 100px;padding-top: 20px;border-bottom: 1px dashed #BCBCBC;}
.zyname{float: left; margin: 25px 190px 0 0;}
.zyarea{float: left; width: 300px;}
.nr{background-color: #F9FCEF;margin: 10px 0 10px 0;height: 25px;padding-top: 5px;border-bottom: 1px solid #BCBCBC;}
#color-box{
    width:112px; 
    height: 100px; 
    position: absolute;
    display: none;
}
#color-box ul{
    border: 1px solid #d2d2d2;
    overflow: hidden;
}
#color-box ul li{
    margin-left: 2px;
    margin-top: 2px;
    float: left;
    display: inline-block;
    width: 20px;
    height: 20px;
    text-align: center;
}
</style>
<div class="rows">
    <div class="widget">
        <h2>更新文章</h2>
        <?php
        // $urlPre = (isset($_GET['urlPre'])&&$_GET['urlPre']) ? $_GET['urlPre'] : '';
        // $url = "/cusAddons/updateCusAddons/id/{$model->id}/urlPre/{$urlPre}";
        $form = $this->beginWidget('CActiveForm',array(
            //'action'=>$url,
            'htmlOptions' => array('enctype'=>'multipart/form-data'), 
        ));?>
            <div class="customer" style="position: relative;">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4">Basic Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $form->label($model,'title'); ?>
                                <?php echo  $form->error($model,'titile');?>
                            </td>
                            <td><?php echo $form->textField($model,'title'); ?></td>
        					                
							<td>
                                <?php echo $form->label($model,'shorttitle'); ?>
                                <?php echo  $form->error($model,'shorttitle');?>
                            </td>
                            <td><?php echo $form->textField($model,'shorttitle'); ?></td>
                        </tr>

                        <tr>
							<td>
                                <?php echo $form->label($model,'cid'); ?>
                                <?php echo  $form->error($model,'cid');?>
                            </td>
                            <td><?php 
                            echo $form->dropDownList($model,'cid',$channelList,array('empty'=>'请选择文章所属栏目'));
                            ?></td>

                            <td>
                                <?php echo $form->label($model,'flag'); ?>
                                <?php echo  $form->error($model,'flag');?>
                            </td>
                            <td><?php echo $form->textField($model,'flag'); ?></td>
                        </tr>

						<tr>
							<td>
                                <?php echo $form->label($model,'color'); ?>
                                <?php echo  $form->error($model,'color');?>
                            </td>
                            <td><?php echo $form->textField($model,'color'); ?></td>

                            <td>
                                <?php echo $form->label($model,'writer'); ?>
                                <?php echo  $form->error($model,'writer');?>
                            </td>
                            <td><?php echo $form->textField($model,'writer'); ?></td>
                        </tr>

                        <tr>
							

                            <td>
                                <?php echo $form->label($model,'notpost'); ?>
                                <?php echo  $form->error($model,'notpost');?>
                            </td>
                            <td>
                            <?php 
                            //echo $form->textField($model,'notpost'); 
                             $list=array(0=>'是',1=>'否');
                            
                            echo $form->dropDownList($model,'notpost',$list); 
                            ?>
                            </td>

                             <td>
                                <?php echo $form->label($model,'keywords'); ?>
                                <?php echo  $form->error($model,'keywords');?>
                            </td>
                            <td><?php echo $form->textField($model,'keywords'); ?></td>

                            
                        </tr>

                        

                    </tbody>
                </table>
                <ul class="litpic">
                    
                    <li class="bqname"><?php echo $form->label($model,'litpic'); ?></li>
                    <?php echo  $form->error($model,'litpic');?>
                    <li class="fileinput"><?php echo $form->fileField($model,'litpic'); ?></li>
                    <li><img style="width:150px;height:100px;" src="<?php echo $model->litpic;?>"></li>
                   
                </ul>
                <div class="zy">
                   
                    <div class="zyname"><?php echo $form->label($model,'description'); ?></div>
                    <?php echo  $form->error($model,'description');?>
              
                    <div class="zyarea"><?php echo $form->textArea($model,'description',array('rows'=>5)); ?></div>
                </div>
                
                <div>
                <div class="nr"><?php echo $form->label($model,'body'); ?></div>
                <?php echo  $form->error($model,'body');?>
                <script id="container" name="body" type="text/plain">
                <?php echo $model->body;?>
                </script>  
                <script type="text/javascript">
                var ue = UE.getEditor('container',{
                    initialFrameHeight: 200,
                });
                $(function(){
                    var cbox  = $('#color-box');
                        $('#ArticleContentModel_color').focus(function(){
                            var pos = $(this).position();
                            var posTop = pos.top+32+'px';
                            var posLeft = pos.left+'px';
                            cbox.css({'top':posTop, 'left':posLeft});
                            cbox.show();
                        });
                        
                        $('.color-none').click(function(){
                            cbox.hide();
                        })

                        var lis = cbox.find('li');
                        lis.each(function(){
                            $(this).click(function(){
                                var c = $(this).attr('data-color')==='#FFFFFF' ? '' : $(this).attr('data-color');
                                $('#ArticleContentModel_color').val(c);
                                cbox.hide();
                            })
                        });
                })
                </script>
                </div>
                <div class="box-in">
                    <button type="submit" class="btn btn-large agnore ">更新</button>
                </div>
                 <div id="color-box">
                    <ul>
                        <?php 
                            foreach (Yii::app()->params['colorList'] as $row) {
                                if($row == '#FFFFFF'){
                                    echo '<li style="background:'.$row.'" data-color="'.$row.'" class="color-none">N</li>';
                                }else{
                                    echo '<li style="background:'.$row.'" data-color="'.$row.'"></li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script src="/js/bootstrap-datepicker.js"></script>