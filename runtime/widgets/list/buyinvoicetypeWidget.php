<option value="">申请类型</option>
<?php foreach((new BuyinvoiceEntity())->buytypeMap as $key => $item){?>
<option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
<?php }?>

 <!-- Powered by baxgsun@163.com -->