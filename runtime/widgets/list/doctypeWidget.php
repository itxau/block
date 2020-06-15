<option value="">文档类别</option>
<?php foreach(FinancialdocsService::documentTypeMap() as $key => $item){?>
    <option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
<?php }?>
 <!-- Powered by baxgsun@163.com -->