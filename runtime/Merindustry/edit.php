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
			<?php echo JS::import('date');?>
<div class="panel-wrapper collapse in">
  <div class="panel-body">
            <div class="col-md-11 col-sm-11 col-xs-11">
              <form action="<?php echo urldecode(Url::urlFormat("/merindustry/save"));?>" class="form-horizontal" id="edit-form" name="edit-form" method="post">
                  <?php if(isset($id) && $id){?>
                  <input type="hidden" name="id" value="<?php echo isset($id)?$id:"";?>">
                  <?php }?>
                  <input type="hidden" name="status" id="status" value="<?php echo isset($status)?$status:"";?>">
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-3">所属名称：</label>
                    <div class="col-md-9 col-sm-9 col-xs-9">
                      <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($name)?$name:"";?>" pattern="required">
                    </div>
                  </div>
                  <div class="form-actions mt-30">
                    <input type='hidden' name='labor_token_form' value='<?php echo Core::app()->getToken("form");?>'/>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-3"></label>
                      <div class="col-md-9 col-sm-9 col-xs-9">
                       
                        <button type="button" class="btn btn-primary mr-20" id="btnsave">保存</button>
                      
                        <!-- <button type="button" class="btn btn-success" id="btnsubmit">提交</button> -->
                       
                      </div>
                    </div>
                  </div>
              </form>
            </div>
    </div>
  </div>
</div>
<?php $widget = Widget::createWidget('public');$widget->_action = "downWidget";$widget->cache = "false";$widget->run();?> 

<script type="text/javascript">
    $("#btnsave").on("click",function(){
      $("#status").val(1);
      $("#edit-form").submit();
    });

    $("#btnsubmit").on("click",function(){
      swal({   
        title: "您确认要提交申请吗？",   
        text: "申请提交后将无法对其修改/删除！",   
        showCancelButton: true,  
        cancelButtonText: "否，暂不提交",   
        confirmButtonColor: "#76c880",   
        confirmButtonText: "是，立刻提交",   
        closeOnConfirm: false 
      }, function(){
        $("#status").val(2);
        $("#edit-form").submit();
        
      });
    });

    $(document).ready(function() {
        $("#taxation_id").val(<?php echo isset($taxation_id)?$taxation_id:"";?>);
        <?php if(isset($upld) && $upld==1){?>
        $("#tabs_1 li").eq(1).addClass('active').siblings().removeClass('active');
        $('#tab_2').addClass('active').siblings().removeClass('active');
        <?php }?>
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