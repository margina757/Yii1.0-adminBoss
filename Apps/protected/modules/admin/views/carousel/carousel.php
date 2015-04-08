<?php
$this->title = '首页轮播图片管理 - '.$this->titleTail;
?>
<style type="text/css">
	.control-group{
		margin-left: 100px; 
		float: left;
	}
	.control-label{
		float: left;
	}
	.control-group img{
		float: left;
		margin-left: 20px; 
		width: 160px;
		height: 100px;
	}
	.controls{
		margin-left: 100px; 
	}
	.containner button{
		width: 100px;
		margin-left: 100px; 
	}
	.btn_box{
		width: 100%;
		clear: both;
	}
</style>
<div class="containner">
<fieldset>
	<legend>
		<h2>首页轮播图片管理</h2>	
	</legend>
	<form action="/admin/Carousel/Index" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<label class="control-label">第一张轮播图</label>
			<img src="<?php echo $carousel[0]['pic']?>" alt="<?php echo $carousel[0]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[0]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[0]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[0]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">第二张轮播图</label>
			<img src="<?php echo $carousel[1]['pic']?>" alt="<?php echo $carousel[1]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[1]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[1]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[1]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">第三张轮播图</label>
			<img src="<?php echo $carousel[2]['pic']?>" alt="<?php echo $carousel[2]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[2]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[2]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[2]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">第四张轮播图</label>
			<img src="<?php echo $carousel[3]['pic']?>" alt="<?php echo $carousel[3]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[3]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[3]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[3]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">第五张轮播图</label>
			<img src="<?php echo $carousel[4]['pic']?>" alt="<?php echo $carousel[4]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[4]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[4]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[4]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">第六张轮播图</label>
			<img src="<?php echo $carousel[5]['pic']?>" alt="<?php echo $carousel[5]['alt']?>">
			<div class="controls" style="clear: both;">
				<input type="file" name="pic[]">
				<input type="hidden" name="oldPic[]" value="<?php echo $carousel[5]['pic']?>">
				<p class="help-block">请上传图片</p>
				<input name="address[]" type="text" maxlength="255" value="<?php echo $carousel[5]['address']?>">
				<p class="help-block">请填写链接地址</p>
				<input name="alt[]" type="text" maxlength="255" value="<?php echo $carousel[5]['alt']?>">
				<p class="help-block">请填写标题</p>
			</div>
		</div>
		<div class="btn_box">
			<button class="btn btn-primary" type="submit" style="clear:both">确定修改</button>	
		</div>
	</form>
</fieldset>
</div>