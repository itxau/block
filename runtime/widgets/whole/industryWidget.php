<option value="">选择行业</option>
<?php foreach(IndustryService::roleMap($status) as $key => $item){?>
	<option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
<?php }?>

 <!-- Powered by baxgsun@163.com -->