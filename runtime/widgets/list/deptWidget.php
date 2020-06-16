<option value="">选择部门</option>
<?php foreach(DeptService::deptMap($status) as $key => $item){?>
	<option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
<?php }?>
 <!-- Powered by baxgsun@163.com -->