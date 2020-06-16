<option value="">发票类别</option>
<?php foreach((new InvoicetypeEntity())->queryAll() as $key => $item){?>
    <option value="<?php echo isset($item['id'])?$item['id']:"";?>"><?php echo isset($item['name'])?$item['name']:"";?></option>
<?php }?>
 <!-- Powered by baxgsun@163.com -->