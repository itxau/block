<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo isset($site_full_name)?$site_full_name:"";?></title>
	<meta name="author" content="designer:sunxiangen, date:2019-10-12" />
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>">
	<link rel="icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>" type="image/x-icon">
	<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery/dist/jquery.min.js"));?>"></script>
	<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.css"));?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/ali-icon/iconfont.css"));?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo urldecode(Url::urlFormat("@static/css/style.css"));?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo urldecode(Url::urlFormat("@static/css/style-fixed.css"));?>" rel="stylesheet" type="text/css"/>

	<?php echo JS::import('dialog?skin=tinysimple');?>
	<?php echo JS::import('dialogtools');?>

</head>
<body>
	<!-- Preloader -->
	<div class="preloader-it">
		<div class="la-anim-1"></div>
	</div>
	<!-- /Preloader -->
    <div class="wrapper theme-6-active pimary-color-pink">
		<!-- Top Menu Items -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="mobile-only-brand pull-left">
				<div class="nav-header pull-left" style=" background: #4c7430; ">
					<div class="logo-wrap">
						<a href="<?php echo urldecode(Url::urlFormat("/admin/index"));?>">
							<img class="brand-img" src="<?php echo urldecode(Url::urlFormat("@static/images/logo2.png"));?>" alt="brand"/>
							<span class="brand-text"><?php echo isset($site_name)?$site_name:"";?></span>
						</a>
					</div>
				</div>	
				<a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);">
					<i class="zmdi zmdi-menu"></i>
				</a>
			</div>
			<div id="mobile_only_nav" class="mobile-only-nav pull-right">
				<ul class="nav navbar-right top-nav pull-right">
					<li class="dropdown">
						<a href="void(0)" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-fw fa-user"></i>
							<?php echo isset($this->manager['name'])?$this->manager['name']:"";?>
							<span class="caret"></span> 
						</a>
						<ul class="dropdown-menu" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
							<li>
								<a id="myinfo"><i class="zmdi zmdi-account"></i><span>我的信息</span></a>
							</li>
							<li>
								<a id="mypassword"><i class="zmdi zmdi-key"></i><span>修改密码</span></a>
							</li>
							<!--
							<li>
								<a id="facetest"><i class="fa fa-meh-o"></i><span>人脸验证</span></a>
							</li>
						-->
							<li class="divider"></li>
							<li>
								<a href="<?php echo urldecode(Url::urlFormat("/taxbureau/logout"));?>"><i class="zmdi zmdi-power"></i><span>登出</span></a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<!-- /Top Menu Items -->
		<!-- Left Sidebar Menu -->
		<div class="fixed-sidebar-left">
			<ul class="nav navbar-nav side-nav nicescroll-bar">
				<li><br></li>
				<!-- User Profile -->
				<!--
				<li>
					<div class="user-profile text-center">
						<img src="<?php echo urldecode(Url::urlFormat("@static/images/shuiwu.png"));?>" class="user-auth-img img-circle"/>
					</div>
				</li>
				<li><hr class="light-grey-hr mb-10"/></li>
			-->
				<!-- /User Profile -->
				<?php foreach($menu->getSubMenu("system") as $key => $main){?>
				<?php if($main["name"]=="hr"){?>
				<li><hr class="light-grey-hr mb-10"/></li>
				<?php }else{?>
				<?php $clink=$currentMenu['link']==$main['url'];?>
				<?php $csub=$currentMenu['subKey']==$main['link'];?>
				<?php if($main['url']){?>
				<li>
					<a <?php if($clink){?> class="active" <?php }?> href="<?php echo urldecode(Url::urlFormat("$main[url]"));?>">
						<div class="pull-left">
							<i class="fa fa-fw <?php echo isset($main['icon'])?$main['icon']:"";?>  mr-10"></i>
							<span class="right-nav-text"><?php echo isset($main['name'])?$main['name']:"";?></span>
						</div>
						<div class="clearfix"></div>
					</a>
				</li>
				<?php }else{?>
				<li>
					<a <?php if($csub){?> class="active" <?php }?> 
					 href="javascript:;" data-toggle="collapse" data-target="#main_<?php echo isset($key)?$key:"";?>">
						<div class="pull-left">
							<i class="fa fa-fw <?php echo isset($main['icon'])?$main['icon']:"";?> mr-10"></i>
							<span class="right-nav-text"><?php echo isset($main['name'])?$main['name']:"";?></span>
						</div>
						<div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div>
						<div class="clearfix"></div>
					</a>
					<ul id="main_<?php echo isset($key)?$key:"";?>" class="collapse collapse-level-1 <?php if($csub){?> in <?php }?>">
						<?php foreach($menu->getNodes($main['link']) as $key => $node){?>
						<?php $currNod=$node["link"]==$currentMenu["link"];?>
						<?php if(substr($node['link'],-4)!='edit'){?>
						<li>
							<a <?php if($currNod){?> class="active" <?php }?>  href="<?php echo urldecode(Url::urlFormat("$node[link]"));?>">
								<i class="fa fa-fw <?php echo isset($node['icon'])?$node['icon']:"";?> mr-10"></i>
								<span class="right-nav-text">
									<?php echo isset($node['name'])?$node['name']:"";?>
								</span>
							</a>
						</li>
						<?php }?>
						<?php }?>
					</ul>
				</li>
				<?php }?>
				<?php }?>
				<?php }?>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
		<!-- /Left Sidebar Menu -->
		<div class="page-wrapper">
            <div class="container-fluid">
            	<div class="row heading-bg">
				    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
				      <h5 class="txt-dark"><?php echo isset($admin_title)?$admin_title:"";?></h5>
				    </div>
				    <!-- Breadcrumb -->
				    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
				      <ol class="breadcrumb">
				        <li><a href="<?php echo urldecode(Url::urlFormat("/taxbureau/index"));?>">首页</a></li>
				        
				        <?php if($currentMenu["nodeKey"]){?>
				        <li><a href="#"><span><?php echo isset($currentMenu["subName"])?$currentMenu["subName"]:"";?></span></a></li>
				        <?php }?>
				        <li class="active"><span><?php echo isset($admin_title)?$admin_title:"";?></span></li>
				      </ol>
				    </div>
				    <!-- /Breadcrumb -->
				</div>
				<div class="panel panel-default card-view">
	            	<!-- Row -->
<div class="row">
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($eptNum)?$eptNum:0;?></span></span>
									<span class="weight-500 uppercase-font block font-13 txt-light">企业数量</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
									<i class="icon-people data-right-rep-icon txt-light"></i>
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
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($docNum)?$docNum:0;?></span></span>
									<span class="weight-500 uppercase-font block txt-light">财务报表</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
									<i class="icon-book-open data-right-rep-icon txt-light"></i>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="panel panel-default card-view pa-0 bg-gradient2">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pa-0">
					<div class="sm-data-box">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-6 text-center pl-0 pr-0 txt-light data-wrap-left">
									<span class="block counter"><span class="counter-anim"><?php echo isset($prdNum)?$prdNum:0;?></span></span>
									<span class="weight-500 uppercase-font block">支付备案</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 txt-light data-wrap-right">
									<i class="icon-flag data-right-rep-icon"></i>
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
									<span class="txt-light block counter"><span class="counter-anim"><?php echo isset($dclNum)?$dclNum:0;?></span></span>
									<span class="weight-500 uppercase-font block txt-light">一键申请</span>
								</div>
								<div class="col-xs-6 text-center  pl-0 pr-0 txt-light data-wrap-right">
									<i class="icon-layers data-right-rep-icon"></i>
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
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view panel-refresh">
			<div class="refresh-container">
				<div class="la-anim-1"></div>
			</div>
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">上链企业数走势图</h6>
				</div>
				<div class="pull-right">
					<a href="javascript:;" class="pull-left inline-block refresh">
						<i class="zmdi zmdi-replay"></i>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div id="e_chart_1" class="" style="height:330px;"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view panel-refresh">
			<div class="refresh-container">
				<div class="la-anim-1"></div>
			</div>
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">风控状态分析图</h6>
				</div>
				<div class="pull-right">
					<a href="javascript:;" class="pull-left inline-block refresh mr-15">
						<i class="zmdi zmdi-replay"></i>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div id="e_chart_2" style="height:330px;"></div>	
				</div>	
			</div>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view panel-refresh">
			<div class="refresh-container">
				<div class="la-anim-1"></div>
			</div>
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">接入文档比例态图(数量)</h6>
				</div>
				<div class="pull-right">
					<a href="javascript:;" class="pull-left inline-block refresh">
						<i class="zmdi zmdi-replay"></i>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div id="e_chart_3" class="" style="height:330px;"></div>	
				</div>	
			</div>
		</div>
	</div>
	
</div>
<!-- /Row -->

<!-- Row -->
<div class="row">
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view panel-refresh">
			<div class="refresh-container">
				<div class="la-anim-1"></div>
			</div>
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">最新支付报备信息(TOP10)</h6>
				</div>
				<div class="pull-right">
					<a href="javascript:;" class="pull-left inline-block refresh">
						<i class="zmdi zmdi-replay"></i>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body row pa-0">
					<div class="table-wrap">
						<div class="table-responsive">
							<table class="table display product-overview border-none table-hover" id="order_table">
								<thead>
									<tr>
										<th>#</th>
										<th>支付方</th>
										<th>收款人</th>
										<th>报备时间</th>
										<th>付汇金额</th>
										<th>报备状态</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($prdlist as $key => $item){?>
										<tr>
											<td><?php echo $key+1;?></td>
											<td><?php echo isset($item["fld_01"])?$item["fld_01"]:"";?></td>
											<td><?php echo isset($item["fld_10"])?$item["fld_10"]:"";?></td>
											<td><?php echo isset($item["created_time"])?$item["created_time"]:"";?></td>
											<td><?php echo isset($item["fld_20"])?$item["fld_20"]:"";?></td>
											<td><span class="label label-<?php echo isset($item['label'])?$item['label']:"";?>"><?php echo isset($item["statusname"])?$item["statusname"]:"";?></span></td>
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
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-default card-view panel-refresh">
			<div class="refresh-container">
				<div class="la-anim-1"></div>
			</div>
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">最新一键申报信息(TOP10)</h6>
				</div>
				<div class="pull-right">
					<a href="javascript:;" class="pull-left inline-block refresh" onclick="dochart(5);">
						<i class="zmdi zmdi-replay"></i>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body row pa-0">
					<div class="table-wrap">
						<div class="table-responsive">
							<table class="table display product-overview border-none table-hover" id="balance_table">
								<thead>
									<tr>
										<th>#</th>
										<th>企业名称</th>
										<th>年份</th>
										<th>季度</th>
										<th>状态</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($dclist as $key => $item){?>
										<tr>
											<td><?php echo $key+1;?></td>
											<td><?php echo isset($item["full_name"])?$item["full_name"]:"";?></td>
											<td><?php echo isset($item["year"])?$item["year"]:"";?></td>
											<td><?php echo isset($item["quarter"])?$item["quarter"]:"";?></td>
											<td><span class="label label-<?php echo isset($item['label'])?$item['label']:"";?>"><?php echo isset($item["statusname"])?$item["statusname"]:"";?></span></td>
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
</div>
<!-- /Row -->
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/waypoints/lib/jquery.waypoints.min.js"));?>"></script>
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery.counterup/jquery.counterup.min.js"));?>"></script>

<!-- EChartJS JavaScript -->
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/echarts/dist/echarts-en.min.js"));?>"></script>
<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/echarts-liquidfill.min.js"));?>"></script>

<script type="text/javascript">
	OrderTrend();

	var titles= ['严重','异常','正常'];
	var datas=[
		{value:34, name:'严重'},
        {value:123, name:'异常'},
        {value:335, name:'正常'},
    ];

	chartPie("风控企业状态图","e_chart_2",titles,datas);
	
	var titles=['财务凭证','会计报表','会计帐册','其他文档'];
	var datas=[
		{value:335, name:'财务凭证'},
        {value:123, name:'会计报表'},
        {value:64, name:'会计帐册'},
        {value:34, name:'其他文档'},
    ];
    chartPie("接入文档比例态图","e_chart_3",titles,datas);
	
	

	
	function OrderTrend() {
		option = {
			title: {
		        text: '最近10天上链企业',
		        //subtext: '最近十天成功交易订单',
		        x: 'center'
		    },
		    xAxis: {
		        type: 'category',
		        data:  ['08','09','10','11','12','13','14','15','16','17']
		    },
		    yAxis: {
		        type: 'value'
		    },
		    series: [{
		        data: [1,0,0,2,0,0,3,0,0,0],
		        type: 'line',
		        //smooth: true
		    }]
		};
		var chart = echarts.init(document.getElementById('e_chart_1'));
		chart.setOption(option);
		chart.resize();
	}

	function chartPie(title,chartId,titles,datas) {
		option = {
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		        orient: 'vertical',
		        left: 'left',
		        data: titles
		    },
		    series : [
		        {
		            name: title,
		            type: 'pie',
		            radius : '55%',
		            center: ['50%', '60%'],
		            data:datas,
		            itemStyle: {
		                emphasis: {
		                    shadowBlur: 10,
		                    shadowOffsetX: 0,
		                    shadowColor: 'rgba(0, 0, 0, 0.5)'
		                }
		            }
		        }
		    ]
		};
		var chart = echarts.init(document.getElementById(chartId));
		chart.setOption(option);
		chart.resize();
	}

	

</script>
	            </div>
            </div>
            <!-- Footer -->
			<footer class="footer container-fluid pl-30 pr-30">
				<div class="row">
					<div class="col-sm-12">
						<p></p>
					</div>
				</div>
			</footer>
			<!-- /Footer -->
        </div>
    </div>
    <!-- /Main Content -->
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
	<script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery.counterup/jquery.counterup.min.js"));?>"></script>
	<!-- Init JavaScript -->
	<script src="<?php echo urldecode(Url::urlFormat("@static/js/init.js"));?>"></script>

	<script type="text/javascript">
		$("#mypassword").on("click",function(){
			open_dialog("<?php echo urldecode(Url::urlFormat("/taxbureau/password"));?>","修改密码",600,300,false);
		});

		$("#myinfo").on("click",function(){
			open_dialog("<?php echo urldecode(Url::urlFormat("/taxbureau/headpic"));?>","我的头像",600,400,false);
		});
	</script>
<?php  $address_id =Constants::TAX_ADDR_ID;?>
	<?php $widget = Widget::createWidget('public');$widget->_action = "layimWidget";$widget->con = "enterprise";$widget->type = "2";$widget->uid = $this->manager["id"];$widget->address_id = $address_id;$widget->cache = "false";$widget->run();?>
</body>
</html>



 <!-- Powered by baxgsun@163.com -->