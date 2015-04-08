<?php
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') { 
    if($code == 'UTF-8'){ 
        $pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
        preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
        return join('', array_slice($t_string[0], $start, $sublen)); 
    }else{ 
        $start = $start*2; 
        $sublen = $sublen*2; 
        $strlen = strlen($string); 
        $tmpstr = ''; 
        for($i=0; $i<$strlen; $i++){ 
            if($i>=$start && $i<($start+$sublen)){ 
                if(ord(substr($string, $i, 1))>129){ 
                    $tmpstr.= substr($string, $i, 2); 
                }else{ 
                    $tmpstr.= substr($string, $i, 1); 
                } 
            } 
            if(ord(substr($string, $i, 1))>129)
                $i++; 
        } 
        if(strlen($tmpstr)<$strlen ) 
            $tmpstr.= "..."; 
        return $tmpstr; 
    } 
}
?>
<div>
	<div style="margin-bottom:10px; color:#D0462B;">查询结果：共有<?php echo $count;?>条数据</div>
    <table class="table">
        <thead>
            <tr>
                 <th>Id</th>
                 <th>文章标题</th>
                 <th>文章内容</th>
                 <th>缩略图</th>
                 <th>标签</th>
                 <th>栏目</th>
                 <th>是否审核</th>
                 <th>点击数</th>
                 <th>创建时间</th>
                 
                <th> 操作 </th>
               
            </tr>
        </thead>
        <tbody>
        	<?php
        		foreach($list as $val){?>
					<tr>
                        <td><?php echo $val['aid'];?></td>
                        <td><?php echo cut_str($val['title'],30);?></td>
                        <td><?php 
                      
                        $con=trim(str_replace('&nbsp;','',strip_tags($val['body'])));
                        echo cut_str($con,40);
                       
                        ?></td>
                        
                        <?php if(empty($val['litpic'])){?>
                            <td>无上传缩略图</td>
                        <?php }else{?>
                              <td><img src="<?php echo $val['litpic'];?>" /></td>
                            
                        <?php }?>

                        <td><?php echo $val['flag'];?></td>
                        <td><?php echo $val['name'];?></td>
                        <td><?php if($val['ismake']){
                                echo '已审核';
                            }else{
                                echo '未审核';
                            }    ?></td>
                        <td><?php echo $val['click'];?></td>
                        <td><?php echo $val['created'];?></td>
                        <td><a href="<?php echo $this->createUrl('delete',array('id'=>$val['aid']));?>" onclick="return confirm('确定删除？')">删除</a> <a href="<?php echo $this->createUrl('updateArticle',array('id'=>$val['aid']));?>">更新</a></td>
                    </tr>
        		<?php }

        	?>
        </tbody>
    </table>
      <?php
            $this->widget('CLinkPager',array(
                'header'=>'',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pager,
                'maxButtonCount'=>10,
                )
            );
        ?>
</div>