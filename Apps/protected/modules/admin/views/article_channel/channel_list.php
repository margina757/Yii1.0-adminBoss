<?php
$this->title = '分类列表 - ' . $this->titleTail;
?>
<style type="text/css">
	.toggle-list li ul {
		display: none;
	}
	.plus .icon-minus {
		display: none;
	}
	.plus.active .icon-minus {
		display: inline-block;
	}
	.plus.active .icon-plus
	 {
		display: none;
	}
	li.pad-l-50 {
		padding-left: 20px;
		position: relative;
	}
	.toggle-menu .plus {
		float: left;
	}
	.toggle-menu .action-group {
		float: right;
	}
	#toggle ul li{
		border-top:1px solid #ddd ;
		line-height: 40px;
	}
	#toggle{
		margin-left: 30px;
	}
</style>
<div class="containner">
	<legend>
		<h2>栏目列表查看</h2>
		<div class="rows">
			<a href="javascript:;" class="btn" data-status="1" id="select_all">全选</a>
			<a href="/admin/ArticleChannel/ChannelAdd/id/0" class="btn">添加顶级栏目</a>
		</div>
	</legend>

	<div id="toggle">
	<fieldset>
		<div id="yw0" class="grid-view">
			<form name="" method="post" action="/admin/ArticleChannel/UpdateAllSort">
				<ul class="toggle-list">
					<?php foreach ($channelData as $key => $value){?>					
					<li class="">
						<div class="toggle-menu clearfix">
							<div class="plus">
								<i class="icon-plus"></i>
								<i class="icon-minus"></i>
								<input class="select-on-check subcheck" value="<?php echo $value['id']?>"  type="checkbox" name="ids[]">
								<?php 
									if($value['ishidden']){
										echo '<span style="color:red">[隐藏]</span>'.$value['name'].' [ID:'.$value['id'].']';
									}else{
										echo $value['name'].' [ID:'.$value['id'].']';
									}
								?>
							</div>
							<div class="action-group">
								<a href="/admin/ArticleChannel/ChannelAdd/id/<?php echo $value['id']?>">增加子类</a>
								<a href="/admin/ArticleChannel/ChannelUpdate/id/<?php echo $value['id']?>">更改</a>
								<a href="/admin/ArticleChannel/ChannelMove/id/<?php echo $value['id']?>">移动</a>
								<a href="/admin/ArticleChannel/ChannelDelete/id/<?php echo $value['id']?>" onclick="return confirm('是否确定删除此栏目？')">删除</a>
								<input type="text" value="<?php echo $value['sort']?>" name="sorts[]" style="width:60px;">
							</div>
						</div>
						<ul class="toggle-list">
							<?php foreach ($value['child'] as $key => $value1){?>						
								<li class="pad-l-50">
									<div class="toggle-menu clearfix">
										<div class="plus ">
											<input class="select-on-check subcheck" value="<?php echo $value1['id']?>"  type="checkbox" name="ids[]">
											<i class="icon-plus"></i>
											<i class="icon-minus"></i>
											<?php 
												if($value1['ishidden']){
													echo '<span style="color:red">[隐藏]</span>'.$value1['name'].' [ID:'.$value1['id'].']';
												}else{
													echo $value1['name'].' [ID:'.$value1['id'].']';
												}
											?>
										</div>
										<div class="action-group">
											<a href="/admin/ArticleChannel/ChannelAdd/id/<?php echo $value1['id']?>">增加子类</a>
											<a href="/admin/ArticleChannel/ChannelUpdate/id/<?php echo $value1['id']?>">更改</a>
											<a href="/admin/ArticleChannel/ChannelMove/id/<?php echo $value1['id']?>">移动</a>
											<a href="/admin/ArticleChannel/ChannelDelete/id/<?php echo $value1['id']?>" onclick="return confirm('是否确定删除此栏目？')">删除</a>
											<input type="text" value="<?php echo $value1['sort']?>" name="sorts[]" style="width:60px;">
										</div>
									</div>
									<ul class="toggle-list">
									<?php foreach ($value1['child'] as $key => $value2){?>
										<li class="pad-l-50">
											<div class="toggle-menu clearfix">
												<div class="plus">
													<i class="icon-plus"></i>
													<i class="icon-minus"></i>
													<input class="select-on-check subcheck" value="<?php echo $value2['id']?>"  type="checkbox" name="ids[]">
													<?php 
														if($value2['ishidden']){
															echo '<span style="color:red">[隐藏]</span>'.$value2['name'].' [ID:'.$value2['id'].']';
														}else{
															echo $value2['name'].' [ID:'.$value2['id'].']';
														}
													?>
												</div>
												<div class="action-group">
													<a href="/admin/ArticleChannel/ChannelAdd/id/<?php echo $value2['id']?>">增加子类</a>
													<a href="/admin/ArticleChannel/ChannelUpdate/id/<?php echo $value2['id']?>">更改</a>
													<a href="/admin/ArticleChannel/ChannelMove/id/<?php echo $value2['id']?>">移动</a>
													<a href="/admin/ArticleChannel/ChannelDelete/id/<?php echo $value2['id']?>" onclick="return confirm('是否确定删除此栏目？')">删除</a>
													<input type="text" value="<?php echo $value2['sort']?>" name="sorts[]" style="width:60px;">
												</div>
											</div>
											<ul class="toggle-list">
											<?php foreach ($value2['child'] as $key => $value3){?>
												<li class="pad-l-50">
													<div class="toggle-menu clearfix">
														<div class="plus">
															<i class="icon-plus"></i>
															<i class="icon-minus"></i>
															<input class="select-on-check subcheck" value="<?php echo $value3['id']?>"  type="checkbox" name="ids[]">
															<?php 
																if($value3['ishidden']){
																	echo '<span style="color:red">[隐藏]</span>'.$value3['name'].' [ID:'.$value3['id'].']';
																}else{
																	echo $value3['name'].' [ID:'.$value3['id'].']';
																}
															?>
														</div>
														<div class="action-group">
															<a href="/admin/ArticleChannel/ChannelAdd/id/<?php echo $value3['id']?>">增加子类</a>
															<a href="/admin/ArticleChannel/ChannelUpdate/id/<?php echo $value3['id']?>">更改</a>
															<a href="/admin/ArticleChannel/ChannelMove/id/<?php echo $value3['id']?>">移动</a>
															<a href="/admin/ArticleChannel/ChannelDelete/id/<?php echo $value3['id']?>" onclick="return confirm('是否确定删除此栏目？')">删除</a>
															<input type="text" value="<?php echo $value3['sort']?>" name="sorts[]" style="width:60px;">
														</div>
													</div>
												</li>
											<?php }?>
											</ul>
										</li>
									<?php }?>
									</ul>
								</li>
							<?php }?>
						</ul>
					</li>
					<?php }?>
				</ul>

				<input type="submit" value="更新排序" class="btn btn-primary" style="margin-bottom:20px;">
			</form>
		</div>
	</fieldset>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#select_all').bind('click',function(){
			if($(this).attr('data-status') == 1){
				$('.subcheck').each(function(){this.checked = true});
				$(this).attr('data-status',0);
				$(this).html('取消');
			}else{
				$('.subcheck').each(function(){this.checked = false});
				$(this).attr('data-status',1);
				$(this).html('全选');
			}
		});
		$('.toggle-list .plus i').click(function() {
			$(this).parent().toggleClass('active');
	            $(this).parents('.toggle-menu').next().toggle();
	        });
	})
</script>