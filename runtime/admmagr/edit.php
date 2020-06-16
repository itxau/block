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
  <form id="edit-form" name="edit-form" class="form-horizontal" action="<?php echo urldecode(Url::urlFormat("/taxmagr/save"));?>" method="post">
    <?php if(isset($id) && $id>0){?>
    <input type="hidden" name="id" value="<?php echo isset($id)?$id:"";?>">
    <?php }?>
    <div class="col-md-11 col-sm-11">
      <?php if(isset($errmsg)){?>
      <div class="message"><?php echo isset($errmsg)?$errmsg:"";?></div>
      <?php }?>
      <div class="col-md-8 col-sm-8">
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 用户名称：</label>
          <div class="col-md-9 col-sm-9">
            <input class="form-control" name="name" id="name" type="text" value="<?php echo isset($name)?$name:"";?>" pattern="required">
          </div>
        </div>
	<div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 真实名称：</label>
          <div class="col-md-9 col-sm-9">
            <input class="form-control" name="username" id="username" type="text" value="<?php echo isset($username)?$username:"";?>" >
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 所属部门：</label>
          <div class="col-md-9 col-sm-9">
            <select class="form-control" name="dept_id" id="dept_id" pattern="required">
              <?php $widget = Widget::createWidget('list');$widget->_action = "deptWidget";$widget->status = "0";$widget->cache = "false";$widget->run();?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 用户角色：</label>
          <div class="col-md-9 col-sm-9">
            <select class="form-control" name="role_id" id="role_id" pattern="required">
                <?php $widget = Widget::createWidget('list');$widget->_action = "roleWidget";$widget->status = "0";$widget->cache = "false";$widget->run();?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">参与互动：</label>
          <div class="col-md-3 col-sm-3">
            <div class="checkbox checkbox-success">
              <input name="is_msgofetp" id="is_msgofetp" type="checkbox" <?php if(isset($is_msgofetp) && $is_msgofetp){?> checked <?php }?> value="1">
              <label for="is_msgofetp"> 企业互动 </label>
            </div>
          </div>
          <div class="col-md-3 col-sm-3">
            <div class="checkbox checkbox-success">
              <input name="is_msgofgov" id="is_msgofgov" type="checkbox" <?php if(isset($is_msgofgov) && $is_msgofgov){?> checked <?php }?> value="1">
              <label for="is_msgofgov"> 政务互动 </label>
            </div>
          </div>
        </div>
        <?php if(!isset($id) || !$id){?>
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 密码：</label>
          <div class="col-md-9 col-sm-9">
            <input class="form-control" name="password" id="password" type="password" autocomplete="new-password"  bind="repassword" pattern=".{6,}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 col-sm-3 control-label">* 密码确认：</label>
          <div class="col-md-9 col-sm-9">
            <input type="password" class="form-control" name="repassword" id="repassword"  bind="password" pattern=".{6,}">
          </div>
        </div>
        <?php }?>
        <div class="form-actions mt-30">
          <div class="form-group">
            <label class="col-md-3 col-sm-3 control-label"></label>
            <div class="col-md-9 col-sm-9">
              <input type='hidden' name='labor_token_form' value='<?php echo Core::app()->getToken("form");?>'/>
              <button type="submit" class="btn btn-sm btn-primary op-save" style="width: 200px;">保存</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="text-center">
          <?php $head_pic=Common::getAvatar(isset($head_pic)?$head_pic:"");?>
          <?php $widget = Widget::createWidget('public');$widget->_action = "headpicWidgetUser";$widget->head_pic = $head_pic;$widget->cache = "false";$widget->run();?>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#dept_id").val(<?php echo isset($dept_id)?$dept_id:"";?>);
    $("#role_id").val(<?php echo isset($role_id)?$role_id:"";?>);
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