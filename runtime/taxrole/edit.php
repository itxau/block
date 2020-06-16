<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($site_full_name)?$site_full_name:"";?></title>
		<meta name="author" content="designer:sunxiangen, date:2018-08-01" />
		<!-- Favicon -->
		<link rel="shortcut icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>">
		<link rel="icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>" type="image/x-icon">
		<!--alerts CSS -->
		<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.css"));?>" rel="stylesheet" type="text/css">
		<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet" type="text/css"/>
			
		<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css"));?>" rel="stylesheet" type="text/css"/>
		<!-- Custom CSS -->
		<link href="<?php echo urldecode(Url::urlFormat("@static/css/style.css"));?>" rel="stylesheet" type="text/css">
		<link href="<?php echo urldecode(Url::urlFormat("@static/css/style-fixed.css"));?>" rel="stylesheet" type="text/css">
		<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery/dist/jquery.min.js"));?>"></script>
		<?php echo JS::import('form');?>
		<?php echo JS::import('dialog?skin=tinysimple');?>
		<?php echo JS::import('dialogtools');?>
	</head>
	<body style="background-color: #fff;">
		<div>
			<div class="panel-body">
  <form id="edit-form" name="edit-form" class="form-horizontal" action="<?php echo urldecode(Url::urlFormat("/taxrole/save"));?>" method="post">
    <?php if(isset($id)){?>
    <input type="hidden" name="id" value="<?php echo isset($id)?$id:"";?>">
    <?php }?>
    <div class="col-md-12 col-sm-12">
      <?php if(isset($errmsg)){?>
      <div class="message"><?php echo isset($errmsg)?$errmsg:"";?></div>
      <?php }?>
      <div class="form-group">
        <label class="col-md-2 col-sm-2 control-label">*角色名称：</label>
        <div class="col-md-9 col-sm-9">
          <input class="form-control" name="name" id="name" type="text" value="<?php echo isset($name)?$name:"";?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2 col-sm-2 control-label">授权：</label>
        <div class="col-md-3 col-sm-3">
          <div class="checkbox checkbox-success">
            <input name="is_grantofetp" id="is_grantofetp" type="checkbox" <?php if(isset($is_grantofetp) && $is_grantofetp){?> checked <?php }?> value="1">
            <label for="is_grantofetp"> 查看企业信息需要授权 </label>
          </div>
        </div>
        <div class="col-md-3 col-sm-3">
          <div class="checkbox checkbox-success">
            <input name="is_grantofgov" id="is_grantofgov" type="checkbox" <?php if(isset($is_grantofgov) && $is_grantofgov){?> checked <?php }?> value="1">
            <label for="is_grantofgov"> 查看政务信息需要授权 </label>
          </div>
        </div>
      </div>
      <?php foreach($modules as $key => $main){?>
      <?php $mkey=$key;?>
      <div>
        <?php echo isset($main)?$main:"";?> <input type="checkbox" class="group" id="items_<?php echo isset($mkey)?$mkey:"";?>">
        <div class="form-group" style="padding:10px 0px 0px 30px;">
          <?php $item=null; $query = new Query("adm_right");$query->where = "module = '$mkey'";$query->order = "sort";$items = $query->find(); foreach($items as $key => $item){?>
          <?php $flag=true;?>
          <?php if(stripos($rights,$item['rights'])===false){?>
             <?php $flag=false;?>
          <?php }?>
          <div class="col-md-3 col-sm-3">
            <label><input type="checkbox" class="items_<?php echo isset($mkey)?$mkey:"";?>" name="right[]" value="<?php echo isset($item['id'])?$item['id']:"";?>" <?php if($flag){?>checked = "checked"<?php }?>> <?php echo isset($item["name"])?$item["name"]:"";?></label>
          </div>
          <?php }?>
        </div>
        <div class="clear"></div>
      </div>
      <?php }?>
      <div class="col-md-offset-1 col-sm-offset-1">
        <input type='hidden' name='labor_token_form' value='<?php echo Core::app()->getToken("form");?>'/>
        <button type="submit" class="btn btn-sm btn-primary btn-save">保存</button>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
//全选
$(".group").each(function(){
  $(this).on("click",function(){
    var id = $(this).attr('id');
    var checked = $(this).is(':checked');
    if (checked) 
      $("."+id).prop('checked','checked');
    else
      $("."+id).removeAttr('checked');
  })
});
//单选某一个互动全选
$("input[name='right[]']").on("click",function(){
  var group = $(this).attr("class");
  var flag = true;
  $("."+group).each(function(){
    var checked = $(this).is(':checked');
    if(!checked){
      flag = false;
      return;
    }
  })
  if (flag) 
    $("#"+group).prop('checked','checked');
  else
    $("#"+group).removeAttr('checked');
});
//设置全选按钮状态
$(".group").each(function(){
  var id = $(this).attr('id');
  var flag = true;
  $("."+id).each(function(){
    var checked = $(this).is(':checked');
    if(!checked){
      flag = false;
      return;
    }
  });
  if (flag) 
    $(this).prop('checked','checked');
  else
    $(this).removeAttr('checked');
});

</script>

		</div>
	</body>
	<!-- JavaScript -->
	<script src="<?php echo urldecode(Url::urlFormat("@static/js/common.js"));?>"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
    <!-- Sweet-Alert  -->
	<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.min.js"));?>"></script>
	
</html>

 <!-- Powered by baxgsun@163.com -->