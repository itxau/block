<option value="">税种类别</option>
<?php foreach((new TaxationEntity())->queryAll() as $key => $item){?>
    <option value="<?php echo isset($item['id'])?$item['id']:"";?>"><?php echo isset($item['name'])?$item['name']:"";?></option>
<?php }?>
 <!-- Powered by baxgsun@163.com -->