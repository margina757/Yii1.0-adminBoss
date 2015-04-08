<link rel="stylesheet" href="/css/datepicker.css">
<?php
$this->title = '回复留言 - ' . $this->titleTail;
?>
<style type="text/css">
	.content{
		
		height: 500px;
	}
	.top{height: 27px;
		line-height: 27px;
		background-image: url('/images/guestbook/rbg.png');
		font-size: 14px;
		font-weight: 600;
		padding-left: 5px;
		
	}
	tr{height: 40px;}
	.button{text-align: center;
		
		height: 30px;
	}
	
</style>
<form action="" method="post">
<div class="content">
	<div class="top">回复留言：</div>
	<table cellpadding="0" cellspacing="0" border="0" class="table">
		<tr>
		<td>留言主题：</td><td><?php echo $model->title;?></td>
		</tr>
		<tr>
		<td>留言人名称： </td>
		<td><?php echo $model->uname;?></td></tr>
		<tr>
		<td>邮箱：</td><td><?php echo $model->email;?></td></tr>
		<tr>
		<td>ip地址：</td><td><?php echo $model->ip;?></td></tr>
		<tr>
		<td>留言内容：</td><td><?php echo $model->msg;?></td></tr>
		<tr><td>管理员回复：</td><td><textarea style="width:370px;height:110px;" name="replay" cols="30" rows="5"></textarea></td></tr>
		
	</table>
	<div class="button"><input style="width:70px;height:23px;" type="submit" value="保存回复"> &nbsp;&nbsp;&nbsp;<input  style="width:70px;height:23px;" type="reset" value="取消回复"></div>
</div>	
</form>