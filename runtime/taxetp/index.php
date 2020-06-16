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
						<a href="<?php echo urldecode(Url::urlFormat("/index/index"));?>">
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
	            	<style type="text/css">
    td >img.head-pic {width: 32px;height: 32px;}
</style>
<form action="<?php echo urldecode(Url::urlFormat("/taxetp/index"));?>" method="post" id="main_form" name="main_form">
    <div class="panel-heading">
        <div class="pull-left">
            <div class="pull-left">
            <div class="button-list">
                <a class="btn btn-sm btn-primary op-new">
                    <i class="fa fa-plus"></i>
                    <span>新企业</span>
                </a>
            </div>
        </div>
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <input type="text" class="form-control seach" id="ct__name_or_ct__full_name" name="ct__name_or_ct__full_name" placeholder="企业名称" value="<?php echo isset($ct__name_or_ct__full_name)?$ct__name_or_ct__full_name:"";?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control seach" id="ct__legal_representative" name="ct__legal_representative" placeholder="企业法人" value="<?php echo isset($ct__legal_representative)?$ct__legal_representative:"";?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control seach" id="ct__socialcredit_no" name="ct__socialcredit_no" placeholder="统一社会信用码" value="<?php echo isset($ct__socialcredit_no)?$ct__socialcredit_no:"";?>">
                </div>
                <div class="form-group">
                    <select class="form-control seach" name="status" id="status">
                        <option value="">选择状态</option>
                        <?php foreach((new EnterpriseEntity())->statusMap as $key => $item){?>
                            <option value="<?php echo isset($key)?$key:"";?>"><?php echo isset($item)?$item:"";?></option>
                        <?php }?>
                    </select>
                </div>
                <button type="button" class="btn btn-primary btn-rounded btn-sm" id="btn_searchId">查找
                </button>
             </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-wrapper">
        <div class="panel-body">
            <div class="table-wrap">
                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>头像</th>
                            <th>企业名称</th>
                            <th>验证手机</th>
                            <th>法人代表</th>
                            <th>最后登录</th>
                            <th>接入时间</th>
                            <th>状态</th>
                            <th style="width: 100px; text-align: center;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data["data"] as $key => $item){?>
                        <tr data-refid="<?php echo isset($item['id'])?$item['id']:"";?>">
                            <td><?php echo Common::gridIndex($data["page"],$key);?></td>
                            <td>
                                <img class="head-pic" src=<?php echo Common::getAvatar($item['head_pic']);?>>
                            </td>
                            <td><?php echo isset($item["full_name"])?$item["full_name"]:"";?></td>
                            <td><?php echo isset($item["mobile"])?$item["mobile"]:"";?></td>
                            <td><?php echo isset($item["legal_representative"])?$item["legal_representative"]:"";?></td>
                            <td><?php echo isset($item["last_login"])?$item["last_login"]:"";?></td>
                            <td><?php echo isset($item["created_time"])?$item["created_time"]:"";?></td>
                            <td><span class="label label-<?php echo isset($item['label'])?$item['label']:"";?>"><?php echo isset($item["statusname"])?$item["statusname"]:"";?></span></td>
                            <td class="text-nowrap text-center">
                                <a href="#" class="mr-5" data-toggle="tooltip" data-original-title="企业信息" onclick="info(<?php echo isset($item['id'])?$item['id']:"";?>)"> 
                                    <i class="fa fa-lg fa-search text-primary"></i> 
                                </a> 
                                <a href="#" class="op-edit mr-5" data-toggle="tooltip" data-original-title="企业信息"> 
                                    <i class="fa fa-lg fa-edit text-primary"></i> 
                                </a>
                                <!--
                                <a href="#" class="mr-5" data-toggle="tooltip" data-original-title="信息发送" onclick="message(<?php echo isset($item['id'])?$item['id']:"";?>,'<?php echo isset($item['name'])?$item['name']:"";?>')"> 
                                    <i class="fa fa-lg fa-comments text-primary"></i> 
                                </a> 
                                <a onclick="analysis(<?php echo isset($item['id'])?$item['id']:"";?>)" data-toggle="tooltip" data-original-title="风控分析"> 
                                    <i class="fa fa-lg fa-shield text-primary"></i> 
                                </a> 
                            -->
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <div class="page_nav">
                <?php echo $data['html'];?>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $widget = Widget::createWidget('public');$widget->_action = "commandWidget";$widget->con = "taxetp";$widget->width = "800";$widget->height = "450";$widget->title = "企业";$widget->cache = "false";$widget->run();?>


<script type="text/javascript">
    $(document).ready(function() {
        $("#status").val("<?php echo isset($status)?$status:"";?>");
    });
</script>

<script type="text/javascript">
   
    function info(id) {
        art.dialog.open("<?php echo urldecode(Url::urlFormat("/taxetp/info/id/"));?>"+id,{id:'edit_dialog',title:'企业信息',resize:false,width:1000,height:500});
        return false;
    }

    function analysis(id) {
        art.dialog.open("<?php echo urldecode(Url::urlFormat("/taxetp/analysis/id/"));?>"+id,{id:'edit_dialog',title:'风控分析',resize:false,width:1000,height:600});
        return false;
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