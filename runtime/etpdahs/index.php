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
                    <div class="container-fluid">
  <form action="<?php echo urldecode(Url::urlFormat("/etpdahs/index"));?>" method="post" id="main_form" name="main_form">
    <div class="panel-heading">
        <div class="pull-left">
            <div class="button-list">
                <a class="btn btn-sm btn-primary op-new">
                    <i class="fa fa-plus"></i>
                    <span>新数据上链</span>
                </a>
            </div>
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <div class="form-group">
                    <select class="form-control" name="year" id="year">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "yearWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="month" id="month">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "monthWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="doctype_id" id="doctype_id">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "doctypeWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" id="btn_searchId">查找
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
                      <th>年月</th>
                      <th>文档类别</th>
                      <th>报表类别</th>
                      <th>区块地址</th>
                      <th>类型</th>
                      <th>上链时间</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach($data["data"] as $key => $item){?>
                  <tr>
                      <td><?php echo Common::gridIndex($data["page"],$key);?></td>
                      <td><?php echo isset($item["year"])?$item["year"]:"";?>-<?php echo str_pad($item["month"],2,'0',STR_PAD_LEFT);?></td>
                      <td><?php echo isset($item["doctype_name"])?$item["doctype_name"]:"";?></td>
                      <td><?php echo isset($item["reptype_name"])?$item["reptype_name"]:"";?></td>
                      <td>
                        <a href="#" style="color: blue;" onclick="down('<?php echo isset($item['address_id'])?$item['address_id']:"";?>','<?php echo isset($item['txid'])?$item['txid']:"";?>')"><?php echo TString::msubstr($item["txid"],0,40);?></a>
                      </td>
                      <td class="text-center"><i class="fa fa-lg <?php echo Common::getExtensionIcon($item['ext']);?>"></i></td>
                      <td><?php echo isset($item["created_time"])?$item["created_time"]:"";?></td>
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
</div>
<?php $widget = Widget::createWidget('public');$widget->_action = "downWidget";$widget->cache = "false";$widget->run();?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#year").val("<?php echo isset($year)?$year:"";?>");
        $("#month").val("<?php echo isset($month)?$month:"";?>");
        $("#doctype_id").val("<?php echo isset($doctype_id)?$doctype_id:"";?>");

        $('.op-new').on('click',function(){
            open_dialog("<?php echo urldecode(Url::urlFormat("/etpdahs/blcup"));?>","数据上链",800,400,false);
            return false;
        });
    });
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