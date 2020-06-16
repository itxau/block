<select class="form-control" name="status" id="status">
	<option value="">选择状态</option>
	<?php foreach($statusMap as $key => $item){?>
		<?php if($key==1 && isset($draft) && $draft=='N'){?>
		//省略
		<?php }else{?>
		<option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
		<?php }?>
	<?php }?>
</select>
 <!-- Powered by baxgsun@163.com -->