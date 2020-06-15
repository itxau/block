<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo isset($site_full_name)?$site_full_name:"";?></title>
    <meta name="author" content="designer:sunxiangen, date:2019-10-12" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>">
    <link rel="icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>" type="image/x-icon">
    <link href="<?php echo urldecode(Url::urlFormat("@static/vendors/ali-icon/iconfont.css"));?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.css"));?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo urldecode(Url::urlFormat("@static/css/style.css"));?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo urldecode(Url::urlFormat("@static/css/style-fixed.css"));?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo urldecode(Url::urlFormat("@static/css/custom-styles.css"));?>" rel="stylesheet" />

    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery/dist/jquery.min.js"));?>"></script>
    <?php echo JS::import('dialog?skin=tinysimple');?>
    <?php echo JS::import('dialogtools');?>

    <style type="text/css">
        .page-body {min-height: 300px;}
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="la-anim-1"></div>
    </div>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo urldecode(Url::urlFormat("/index/index"));?>">
                <img src="<?php echo urldecode(Url::urlFormat("@static/images/logo_nin2.png"));?>" style="height: 40px;">
                </a>
            </div>
            <ul class="nav navbar-top-links navbar-right" style="margin-right: 20px; padding-top:10px; ">
                <li class="dropdown">
                    <a href="void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-fw fa-user"></i>
                        <?php echo isset($this->enterprise["name"])?$this->enterprise["name"]:"";?>
                        <span class="caret"></span> 
                    </a>
                    <ul class="dropdown-menu" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <li>
                            <a id="myaccount"><i class="zmdi zmdi-account"></i><span>账号信息</span></a>
                        </li>
                        <li>
                            <a id="mypassword"><i class="zmdi zmdi-key"></i><span>修改密码</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo urldecode(Url::urlFormat("/enterprise/logout"));?>"><i class="zmdi zmdi-power"></i><span>登出</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <nav class="navbar-default navbar-side">
            <div class="sidebar">
                <ul class="nav">
                <?php foreach($menus as $key => $item){?>
                <?php if((!$item["hidden"])){?>
                    <li>
                        <a <?php if($currentUrl==$key){?> class='active-menu' <?php }?> href="<?php echo urldecode(Url::urlFormat("$key"));?>">
                            <i class="fa fa-lg <?php echo isset($item['icon'])?$item['icon']:"";?>"></i><?php echo isset($item['title'])?$item['title']:"";?>
                        </a>
                    </li>
                <?php }?>
                <?php }?>
                </ul>
            </div>
        </nav>
		<div id="page-wrapper">
            <div id="container-fluid">
                <div class="row" style="padding: 15px 0px;">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                      <h5 class="txt-dark" style="padding-top: 10px;"><?php echo isset($title)?$title:"";?></h5>
                    </div>
                    <!-- Breadcrumb -->
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                      <ol class="breadcrumb">
                        <li><a href="<?php echo urldecode(Url::urlFormat("/enterprise/index"));?>">首页</a></li>
                        <li class="active"><span><?php echo isset($title)?$title:"";?></span></li>
                      </ol>
                    </div>
                </div>
                <div class="page-body">
                    
<!-- Row -->
<div class="row">
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient2">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($num1)?$num1:"";?></span></span>
									<span class="weight-500 uppercase-font block font-13 txt-light">财务凭证</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
									<i class="iconfont icon-activity_fill data-right-rep-icon txt-light"></i>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient1">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($num2)?$num2:"";?></span></span>
									<span class="weight-500 uppercase-font block txt-light">会计报表</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
									<i class="iconfont icon-huizongshenqingdanguanli data-right-rep-icon txt-light"></i>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 txt-light data-wrap-left">
									<span class="block counter"><span class="counter-anim"><?php echo isset($num3)?$num3:"";?></span></span>
									<span class="weight-500 uppercase-font block">会计帐册</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 txt-light data-wrap-right">
									<i class="iconfont icon-createtask_fill data-right-rep-icon"></i>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient3">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($num4)?$num4:"";?></span></span>
									<span class="weight-500 uppercase-font block txt-light">其他文档</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 txt-light data-wrap-right">
									<i class="iconfont icon-yundidanguanli data-right-rep-icon"></i>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Row -->
<!-- Row -->
<div class="row">
	<div class="panel panel-default card-view panel-refresh">
		<div class="refresh-container">
			<div class="la-anim-1"></div>
		</div>
		<div class="panel-heading">
			<div class="pull-left">
				<h6 class="panel-title txt-dark">最新 10 条上链数据</h6>
			</div>
			<div class="pull-right">
				<a href="javascript:;" class="pull-left inline-block refresh" onclick="dochart(4);">
					<i class="zmdi zmdi-replay"></i>
				</a>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="panel-wrapper collapse in">
			<div class="panel-body row pl-15 pr-15">
				<div class="table-wrap">
					<div class="table-responsive">
						<table class="table display product-overview border-none table-hover" id="order_table">
							<thead>
								<tr>
			                      <th>#</th>
			                      <th>年月</th>
			                      <th>文档类别</th>
			                      <th>报表类别</th>
			                      <th>区块地址</th>
			                      <th>类型</th>
			                      <th>上链时间</th>
			                  </tr>
							</thead>
							<tbody>
								<?php foreach($data as $key => $item){?>
				                  <tr>
				                      <td><?php echo $key+1;?></td>
				                      <td><?php echo isset($item["year"])?$item["year"]:"";?>-<?php echo str_pad($item["month"],2,'0',STR_PAD_LEFT);?></td>
				                      <td><?php echo isset($item["doctype_name"])?$item["doctype_name"]:"";?></td>
				                      <td><?php echo isset($item["reptype_name"])?$item["reptype_name"]:"";?></td>
				                      <td><?php echo isset($item["txid"])?$item["txid"]:"";?></td>
				                      <td class="text-center"><i class="fa fa-lg <?php echo Common::getExtensionIcon($item['ext']);?>"></i></td>
				                      <td><?php echo isset($item["created_time"])?$item["created_time"]:"";?></td>
				                  </tr>
				                  <?php }?>
							</tbody>
						</table>
					</div>
				</div>	
			</div>	
		</div>
	</div>
</div>
<!-- /Row -->

<!-- /Row -->
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/waypoints/lib/jquery.waypoints.min.js"));?>"></script>
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery.counterup/jquery.counterup.min.js"));?>"></script>

<script type="text/javascript">

	for(i=4;i<=5;i++) {
		dochart(i);
	}

	function dochart(num) {
		switch(num) {
			case 4:
				
				break;
			case 5:
				
				break;
		}
	}

	function dochartAjax(url,callback) {
		$.ajax({
			type: 'POST',
			url: url,
			dataType:"json",
			success:function(result){
				if (result["status"]=="success") {
					callback(result);
				}
				else {
					swal(result["msg"]);
				}
			},
			error:function(result) {
				swal("数据出现错误.");  
			}
	    });
	}

	function orderTableData(tableData) {
		$("#order_table tbody").html("");
		var html="";
		$.each(tableData["data"], function(i,item){
			idx=i+1;
			html+="<tr><td>" + idx + "</td>";
			html+="<td>" + item["order_no"] + "</td>";
			html+="<td>" + item["created_time"] + "</td>";
			html+="<td>" + item["amount"] + "</td>";
			html+="<td><span class='label label-" + item["label"] + "'>" + item["statusname"] + "</span></td></tr>";
		});
		$("#order_table tbody").append(html);
	}

	function balanceTableData(tableData) {
		$("#balance_table tbody").html("");
		var html="";
		$.each(tableData["data"], function(i,item){
			idx=i+1;
			html+="<tr><td>" + idx + "</td>";
			html+="<td>" + item["order_no"] + "</td>";
			html+="<td>" + item["created_time"] + "</td>";
			html+="<td><span class='label label-" + item["label"] + "'>" + item["typename"] + "</span></td>";
			html+="<td>" + item["actual_amt"] + "</td></tr>";
		});
		$("#balance_table tbody").append(html);
	}
</script>
                </div>
            </div>
        </div>
       

    </div>
    <!-- /#wrapper -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrapvalidator/dist/js/bootstrapValidator.min.js"));?>"></script>
    <!-- JavaScript -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/js/common.js"));?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/js/bootstrap.min.js"));?>"></script>
    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/switchery/dist/switchery.min.js"));?>"></script>
    <!-- Slimscroll JavaScript -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/js/jquery.slimscroll.js"));?>"></script>
    <!-- Sweet-Alert  -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.min.js"));?>"></script>
    <!-- Init JavaScript -->
    <script src="<?php echo urldecode(Url::urlFormat("@static/js/init.js"));?>"></script>

    <script type="text/javascript">
        $("#mypassword").on("click",function(){
            open_dialog("<?php echo urldecode(Url::urlFormat("/enterprise/password"));?>","修改密码",600,300,false);
        });

        $("#myaccount").on("click",function(){
            open_dialog("<?php echo urldecode(Url::urlFormat("/enterprise/account"));?>","我的账号",730,400,false);
        });
    </script>
    <script type="text/javascript">
        function setHeight() {
            var height = $(window).height();
            //var h2=screen.height;
            //if (height<h2) height=h2;
            
            //$('#page-wrapper').css('height', (height));
            $('#page-wrapper').css('min-height', (height));
        }
        setHeight();
    </script>
<?php $widget = Widget::createWidget('public');$widget->_action = "layimWidget";$widget->con = "enterprise";$widget->type = "1";$widget->uid = $this->enterprise["id"];$widget->address_id = $this->enterprise["address_id"];$widget->cache = "false";$widget->run();?>
</body>
</html>
 <!-- Powered by baxgsun@163.com -->