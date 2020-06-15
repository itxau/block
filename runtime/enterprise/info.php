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
                    <?php echo JS::import('form');?>
<link href="<?php echo urldecode(Url::urlFormat("@static/vendors/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css"));?>" rel="stylesheet" type="text/css"/>
<div class="panel-wrapper collapse in">
  <div class="panel-body">
    <form class="form-horizontal" action="<?php echo urldecode(Url::urlFormat("/enterprise/info_act"));?>" method="post" id="main_form" name="main_form">
      <input type="hidden" name="id" value="<?php echo isset($id)?$id:"";?>">
      <div class="tab-struct custom-tab-1">
        <ul role="tablist" class="nav nav-tabs" id="tabs">
          <li class="active">
            <a data-toggle="tab" role="tab" href="#tab_1">账户信息</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab"  href="#tab_2">基本信息</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#tab_4">税务信息</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab"  href="#tab_3">法人信息</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#tab_5">股东信息</a>
          </li>
        </ul>
        <div class="tab-content" id="tabcontent">
          <!-- 账户信息 -->
          <div  id="tab_1" class="tab-pane fade active in" role="tabpanel">
            <div class="col-md-11 col-sm-11">
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">企业账号：</label>
                  <label class="col-md-8 col-sm-8 control-label text-left"><?php echo isset($account)?$account:"";?></label>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">注册日期：</label>
                  <label class="col-md-8 col-sm-8 control-label text-left"><?php echo isset($created_time)?$created_time:"";?></label>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">验证手机：</label>
                  <label class="col-md-8 col-sm-8 control-label text-left"><?php echo isset($mobile)?$mobile:"";?></label>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">状态：</label>
                  <label class="col-md-8 col-sm-8 control-label text-left"><?php echo isset($statusname)?$statusname:"";?></label>
                </div>
              </div>
              <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="col-md-2 col-sm-2 control-label">密钥信息：</label>
                  <label class="col-md-10 col-sm-10 control-label text-left" id="lblkey">**************************************************<a href="javascript:;" onclick="sendSMS();"> <i class="fa fa-lg fa-search" alt="查看密钥"></i> </a></label>

                  <div class="col-md-10 col-sm-10 hidden" id="textdiv">
                    <textarea  class="form-control" id="txtkey" name="txtkey" rows="15" readonly></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 账户信息 -->
          <!-- 基本信息 -->

          <div  id="tab_2" class="tab-pane fade" role="tabpanel">
            <div class="col-md-11 col-lg-11 col-sm-11">
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">*企业名称：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="name" value="<?php echo isset($name)?$name:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">*企业全名：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="full_name" value="<?php echo isset($full_name)?$full_name:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">成立日期：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="Wdate form-control" name="establish_date" id="establish_date" onfocus="WdatePicker()" value="<?php echo isset($establish_date)?$establish_date:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">登记状态：</label>
                  <div class="col-md-8 col-sm-8">
                    <select class="form-control" name="registration_status" id="registration_status">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "registrationstatusWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">注册资本：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="registered_capital" value="<?php echo isset($registered_capital)?$registered_capital:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">实纳资本：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="real_capital" value="<?php echo isset($real_capital)?$real_capital:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">企业类型：</label>
                  <div class="col-md-8 col-sm-8">
                    <select class="form-control" name="enterprise_type" id="enterprise_type">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "enterprisetypeWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">参保人数：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="insured_persons" value="<?php echo isset($insured_persons)?$insured_persons:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">人员规模：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="staff_num" value="<?php echo isset($staff_num)?$staff_num:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">企业电话：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="tel" value="<?php echo isset($tel)?$tel:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">企业传真：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="fax" value="<?php echo isset($fax)?$fax:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">经营地址：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="addr" value="<?php echo isset($addr)?$addr:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="col-md-2 col-sm-2 control-label">经营范围：</label>
                  <div class="col-md-10 col-sm-10">
                    <textarea class="form-control" name="business_scope"><?php echo isset($business_scope)?$business_scope:"";?></textarea>
                  </div>
                </div>
              </div>
             
            </div>
          </div>
          <!-- 基本信息 -->
          <!-- 法人信息 -->
          <div  id="tab_3" class="tab-pane fade" role="tabpanel">
            <div class="col-md-11 col-sm-11">
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">法人姓名：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="legal_representative" value="<?php echo isset($legal_representative)?$legal_representative:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">联系电话：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="legal_mobile" value="<?php echo isset($legal_mobile)?$legal_mobile:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">法人证件类型：</label>
                  <div class="col-md-8 col-sm-8">
                    <select class="form-control" name="legal_identificationtype" id="legal_identificationtype">
                        <?php $widget = Widget::createWidget('list');$widget->_action = "identificationWidget";$widget->cache = "false";$widget->run();?>
                      </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">证件号码：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="legal_identificationo" value="<?php echo isset($legal_identificationo)?$legal_identificationo:"";?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 基本信息 -->
          <!-- 税务信息 -->
          <div  id="tab_4" class="tab-pane fade" role="tabpanel">
            <div class="col-md-11 col-sm-11">
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">统一社会信用代码：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="socialcredit_no" value="<?php echo isset($socialcredit_no)?$socialcredit_no:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">纳税人识别号：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="taxpayer_no" value="<?php echo isset($taxpayer_no)?$taxpayer_no:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">工商注册号：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="registration_no" value="<?php echo isset($registration_no)?$registration_no:"";?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">组织机构代码：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="organization_no" value="<?php echo isset($organization_no)?$organization_no:"";?>">
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">出口退税企业?：</label>
                  <div class="col-md-8 col-sm-8">
                    <div class="checkbox checkbox-success">
                      <input name="is_returntax" id="is_returntax" type="checkbox" <?php if(isset($is_returntax) && $is_returntax){?> checked <?php }?> value="1">
                      <label for="is_returntax"> 是</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">纳税信用等级：</label>
                  <div class="col-md-8 col-sm-8">
                    <select class="form-control" name="taxlevel" id="taxlevel">
                      <?php $widget = Widget::createWidget('list');$widget->_action = "taxlevelWidget";$widget->cache = "false";$widget->run();?>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">一般纳税人?：</label>
                  <div class="col-md-8 col-sm-8">
                    <div class="checkbox checkbox-success">
                      <input name="is_general_taxpayer" id="is_general_taxpayer" type="checkbox" <?php if(isset($is_general_taxpayer) && $is_general_taxpayer){?> checked <?php }?> value="1">
                      <label for="is_general_taxpayer"> 是</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">纳税责任人：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="tax_responsible" value="<?php echo isset($tax_responsible)?$tax_responsible:"";?>">
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">纳税人状态?：</label>
                  <div class="col-md-8 col-sm-8">
                    <div class="checkbox checkbox-success">
                      <input name="tax_status" id="tax_status" type="checkbox" <?php if(isset($tax_status) && $tax_status){?> checked <?php }?> value="1">
                      <label for="tax_status"> 是</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="form-group">
                  <label class="col-md-4 col-sm-4 control-label">纳税机关：</label>
                  <div class="col-md-8 col-sm-8">
                    <input type="text" class="form-control" name="tax_office" value="<?php echo isset($tax_office)?$tax_office:"";?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 税务信息 -->
          <!-- 股东信息 -->
          <div  id="tab_5" class="tab-pane fade" role="tabpanel">
            <table class="table table-responsive table-hover">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>股东名称</th>
                      <th>股东类型</th>
                      <th>持股比例</th>
                      <th>认缴出资额</th>
                      <th>
                        <a class="btn btn-sm btn-primary" onclick="addgudong();">
                          <i class="fa fa-plus"></i>
                          <span>新增</span>
                        </a>
                      </th>
                  </tr>
              </thead>
              <tbody id="gudongBody">
                  
              </tbody>
            </table>
          </div>
          <!-- 股东信息 -->
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="form-actions pt-25">
        <div class="form-group">
          <label class="col-md-2 col-sm-2 control-label"></label>
          <div class="col-md-10 col-sm-10">
            <input type='hidden' name='labor_token_form' value='<?php echo Core::app()->getToken("form");?>'/>
            <button type="submit" class="btn btn-primary" style="width: 180px;">保存</button>
          </div>
        </div>
      </div>
  </form>
</div>

<div id="dialog_box" style="display: none;width:450px;padding: 5px;" class="box">
  <form id="dialog_form" callback="doSubmit">
    <div class="panel-wrapper collapse in">
      <div class="panel-body">
        <div class="col-md-11 col-sm-11">
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" name="mobile" value="<?php echo isset($mobile)?$mobile:"";?>" id="mobile" readonly>
              <div class="input-group-addon" style="cursor: pointer;" id="sendSMS" status="">获取验证码</div>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="code" pattern="\d{6}" placeholder="请输入你收到的验证码">
          </div>
          <div class="form-actions mt-30">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100px;"> 查看</button>
            </div>
          </div>
      </div>
    </div>
  </form>
</div>

<div id="dialog_gudong" style="display: none;width:650px;padding: 5px;" class="box">
  <form id="form_gudong" callback="saveGudong" class="form-horizontal">
    <div class="panel-wrapper collapse in">
      <div class="panel-body">
        <div class="col-md-11 col-sm-11">
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-3 control-label text-right">股东姓名：</label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <input class="form-control" name="name" type="text" pattern="required" alt="股东姓名不能为空">
              <label class="valid-msg"></label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-3 control-label text-right">股东类型：</label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <select name="type" class="form-control" pattern="required" alt="股东类型不能为空">
                <option></option>
                <option value="1">自然人股东</option>
                <option value="2">企业股东</option>
              </select>
              <label class="valid-msg"></label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-3 control-label text-right">持股比例：</label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <input class="form-control" name="hold_rate" type="text" pattern="float" alt="持股比例不能为空">
              <label class="valid-msg"></label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-3 control-label text-right">认缴出资额：</label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <input class="form-control" name="invested_amount" type="text" pattern="float" alt="认缴出资额不能为空">
              <label class="valid-msg"></label>
            </div>
          </div>
          <div class="form-actions mt-30">
             <label class="col-md-3 col-sm-3 col-xs-3 control-label"></label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <button type="submit" class="btn btn-primary" style="width: 100px;"> 保存</button>
            </div>

          </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).on('bjui.beforeLoadNavtab',function(e){
    var $navtab=$(e.target)
    alert("ddd");
  });

  $(function(){
    $("#registration_status").val(<?php echo isset($registration_status)?$registration_status:"";?>);
    $("#taxlevel").val(<?php echo isset($taxlevel)?$taxlevel:"";?>);
    $("#enterprise_type").val(<?php echo isset($enterprise_type)?$enterprise_type:"";?>);
    $("#legal_identificationtype").val(<?php echo isset($legal_identificationtype)?$legal_identificationtype:"";?>);
    getgudong();
  });

  function getgudong() {
    $.ajax({
      type: 'get',
      url: "<?php echo urldecode(Url::urlFormat("/enterprise/getgudong"));?>",
      dataType:"json",
      success:function(result){
        if(result["status"]=="success"){
          var html="";
          var idx=0;
          var data=result["data"];
          $.each(data, function(i,item){  
            idx=i+1; 
            html+="<tr><td>" + idx + "</td>";
            html+="<td>" + item["name"] + "</td>";
            html+="<td>" + item["typename"] + "</td>";
            html+="<td>" + item["hold_rate"] + "</td>";
            html+="<td>" + item["invested_amount"] + "</td>";
            html+="<td><a class='btn btn-sm btn-danger' onclick='deleteGudong(" + item["id"] + ")'><i class='fa fa-fw fa-remove'></i>删除</a></td></tr>";
          });
          $("#gudongBody").html(html);
        }
      },
      error:function() {
        swal("获取股东信息发生错误！");
      }
    });
  }

  var submited=false;

  function doSubmit(e) {
      if (submited) return;
      if (e) return;

      var fields = $("#dialog_form").serializeArray();
      submited=true;
      $.ajax({
          type: 'post',
          url: "<?php echo urldecode(Url::urlFormat("/enterprise/privatekeybycode"));?>",
          data: fields,
          dataType:"json",
          success:function(result){
              if(result["status"]=="success"){
                $("#lblkey").hide();
                $("#txtkey").val(result["msg"]);
                $("#textdiv").removeClass("hidden");
                art.dialog({id: 'dialog_box'}).close();
              }
              else {
                  swal(result.msg);
              }
              submited=false;
          },
          error:function() {
            swal("发生错误，操作可能失败");
            submited=false;
          }
      });
      return false;
  }

  function deleteGudong(id) {
    $.ajax({
        type: 'post',
        url: "<?php echo urldecode(Url::urlFormat("/enterprise/gudong_dele"));?>",
        data: {id:id},
        dataType:"json",
        success:function(result){
            if(result["status"]=="success"){
              getgudong();
              swal("股东信息已成功删除");
            }
            else {
              swal(result['msg']);
            }
        },
        error:function() {
          swal("发生错误，操作可能失败");
        }
    });
    return false;

  }

  function saveGudong(e) {
    if (submited) return;
    if (e) return;
    var fields = $("#form_gudong").serializeArray();
    submited=true;
    $.ajax({
        type: 'post',
        url: "<?php echo urldecode(Url::urlFormat("/enterprise/gudong_save"));?>",
        data: fields,
        dataType:"json",
        success:function(result){
            if(result["status"]=="success"){
              getgudong();
              art.dialog({id: 'dialog_gudong'}).close();
            }
            else {
                swal(result['msg']);
            }
            submited=false;
        },
        error:function() {
          swal("发生错误，操作可能失败");
          submited=false;
        }
    });
    return false;
  }

  function addgudong() {
     art.dialog({
      id: 'dialog_gudong', 
      lock: true, 
      title: '新增股东', 
      width: 650, 
      height: 200,  
      content: document.getElementById("dialog_gudong")
    });
    return false;
  }


  function sendSMS() {
     art.dialog({
      id: 'dialog_box', 
      lock: true, 
      title: '密钥查看', 
      width: 450, 
      height: 200,  
      content: document.getElementById("dialog_box")
    });
    return false;
  }

  // #sendSMS.click
  $("#sendSMS").click(function(event) {
      var btn = $("#sendSMS");
      if (btn.attr("status")=="disabled") return;
      var mobi=$("#mobile").val();
      if(mobi=="") {
          swal("手机号码不正确！");
          return false;
      }

      btn.attr("status","disabled");

      $.ajax({
          type: "get",
          url: "<?php echo urldecode(Url::urlFormat("/index/sendsms"));?>",
          data: {mobile:mobi,type:'check'},
          dataType: 'json',
          success: function(result) {
              if (result['status'] == 'success') {
                  var i = 120;
                  btn.html(i + '秒');
                  var timer = setInterval(function() {
                      i--;
                      btn.html(i + '秒');
                      if (i <= 0) {
                          clearInterval(timer);
                          btn.html('获取验证码');
                          btn.attr("status", "");
                      }
                  }, 1000);
              } else {
                  swal(result['msg']);
              }
          }
      });
      return false;
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