<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo isset($site_full_name)?$site_full_name:"";?></title>
        <meta name="author" content="designer:sunxiangen, date:2019-10-12" />
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>">
        <link rel="icon" href="<?php echo urldecode(Url::urlFormat("@favicon.ico"));?>" type="image/x-icon">
       
        <link href="<?php echo urldecode(Url::urlFormat("@static/css/style.css"));?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.css"));?>" rel="stylesheet" type="text/css">
        <link href="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/css/bootstrap.min.css"));?>" rel="stylesheet" type="text/css"/>
        <?php echo JS::import('form');?>
        
        <style type="text/css">
            .addonext {
                padding:0px;background-color: #1a78c2;border-color: #1a78c2;
            }
            #captcha_img {border: 0px;cursor: pointer;}
            .btn-rounded {padding: 8px 25px; width: 220px;margin-top: 10px;}
            .message {text-align: center;color: red;}
            .auth-form {float: right;padding: 30px 50px;}
            .login-wrapper{
                position:fixed;top: 0; left: 0; width:100%; height:100%;
                min-width: 1000px;z-index:-10;zoom: 1;background-color: #fff;
                background-repeat: no-repeat;background-size: cover;
                -webkit-background-size: cover;
                -o-background-size: cover;
                background-position: center 0;
            }
            .sp-logo-wrap img{
                height: 30px;width: 30px; display:inline;vertical-align:middle;
            }
            .sp-logo-wrap span{ vertical-align:middle;}
            .auth-form-wrap > .auth-form{ margin-right: 100px; }
        </style>
    </head>
    <body>
        <!-- Preloader -->
        <div class="preloader-it">
            <div class="la-anim-1"></div>
        </div>
        <div class="login-wrapper login-bg">
            <style type="text/css">
    .login-bg {
        background-image: url(<?php echo urldecode(Url::urlFormat("@static/images/tax_login.jpg"));?>);
    }
</style>
<?php echo JS::import('dialog?skin=tinysimple');?>
<?php echo JS::import('dialogtools');?>
<script type="text/javascript" src="<?php echo urldecode(Url::urlFormat("@static/js/face/easyXDM.min.js"));?>"></script>
<header class="sp-header pa-10">
    <div class="sp-logo-wrap pull-left">
        <a href="<?php echo urldecode(Url::urlFormat("/index/index"));?>">
            <img src="<?php echo urldecode(Url::urlFormat("@static/images/logo2.png"));?>">
            <span class="brand-text">区块链云平台</span>
        </a>
    </div>
    <div class="clearfix"></div>
</header>
<div class="table-struct full-width full-height">
    <div class="table-cell vertical-align-middle auth-form-wrap">
        <div class="auth-form card-view">
            <div>
                <h3 class="text-center txt-dark mb-10">
                    <strong><?php echo isset($site_full_name)?$site_full_name:"";?></strong>
                </h3>
                <p class="text-center message">请输入你的账号信息</p>
            </div>  
            <div class="form-wrap">
                <form id="form_login">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-check"></i></div>
                            <input type="text" class="form-control" name="verifyCode">
                            <div class="input-group-addon addonext"><img id="captcha_img" src='<?php echo urldecode(Url::urlFormat("/index/captcha"));?>' onclick="this.src='<?php echo urldecode(Url::urlFormat("/index/captcha/random/"));?>'+Math.random()"/></div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <input type='hidden' name='labor_token_form' value='<?php echo Core::app()->getToken("form");?>'/>
                        <button type="button" class="btn btn-primary btn-rounded" onclick="uslogin();" id="btnLogin">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>  
</div>

<div id="face_box" style="display: none;width:520px;height:520px; padding:0px; background-color: #213838;" class="box">
    <input type="hidden" name="userface" id="userface" value="" /> 
    <input type="hidden" name="username" id="username" value="" /> 
    <div id="facecontainer" style="width: 520px;height: 516px;"></div>
</div>

<script type="text/javascript">
    var loging=false;
    function uslogin() {
        if (loging) return false;
        loging=true;
        var name=$("#name").val();
        var password=$("#password").val();
        var verifyCode=$("#verifyCode").val();
        if (name==""||password==""||verifyCode=="") {
            swal("请输入完整后再登录！");
            return false;
        }
        $("#btnLogin").text("登录中...");
        $("#btnLogin").attr("disabled","disabled");
        var datas = $("#form_login").serializeArray();
        $.ajax({
            type: 'POST',
            url: "<?php echo urldecode(Url::urlFormat("/admin/login_act"));?>",
            data: datas,
            dataType:"json",
            success:function(result){
                loging=false;
                $("#btnLogin").text("登录");
                $("#btnLogin").removeAttr("disabled");
                if(result.status=="success"){
                    
                    $("#userface").val(result.userface);
                    $("#username").val(result.username);
                  //  faceLogin();
                    
                    window.location.href="<?php echo urldecode(Url::urlFormat("/admin/index"));?>";
                } else {
                    swal(result.msg);
                }
            },
            error:function() {
                loging=false;
                $("#btnLogin").text("登录");
                $("#btnLogin").removeAttr("disabled");
                swal("登录失败");
            }
        });
        return false;
    }
</script>

<script type="text/javascript">

    function faceLogin() {
        $('#facecontainer').html("");
        var title = "人脸验证";
        art.dialog({
            id: 'face_box', lock: true,  
            title: title, width: 520, height: 520, 
            padding: "0px", 
            content: document.getElementById("face_box")
        });

        var domain = "http://www.nfcsjmj.com/ZTCloud/"; 
        var isIE=checkIE();

        var faceUrl = domain + 'appFaceIDInterface/login_toFaceLogin.do';
        faceUrl += '?USERNAME='+$("#username").val();  
        faceUrl += '&AUTHWAY=0'; //0-登录,1-注册
        faceUrl += '&SRC=1';     //0-内部使用,1-区块链使用
        faceUrl += '&isIE=true' ;

        var socket;
        var generateRpc = function (url) {
            return new easyXDM.Rpc({
                isHost: true,
                remote: url,
                hash: false,
                protocol: '1',
                container: document.getElementById('facecontainer'),
                props: {
                    frameBorder: 0,
                    scrolling: 'no',
                    style: {width: '100%', height: '100%'}
                },
                onReady: function (msg) {
                    socket.pingIframe($("#userface").val(), function(response){
                        
                    }, function(errorObj){
                        swal("人脸验证发送错误！");
                    });
               }
            },
            {
                local: {
                    echo: function (message) {
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo urldecode(Url::urlFormat("/admin/facelogin"));?>",
                            data: {username:$("#username").val()},
                            dataType:"json",
                            success:function(result){
                                if(result.status=="success"){
                                    window.location.href="<?php echo urldecode(Url::urlFormat("/taxbureau/index"));?>"
                                } else {
                                    swal("人脸验证失败！");
                                }
                            },
                            error:function() {
                                swal("人脸验证失败");
                            }
                        });
                    }
                },
                remote: {
                    pingIframe: {}
                }
            });
        };
        socket = generateRpc(faceUrl);
    }
         
    function checkIE() {
        if(!!window.ActiveXObject || "ActiveXObject" in window){
          return true;
        }
        return false;
    }
</script>
                            
        </div>
        <!-- jQuery -->
        <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/jquery/dist/jquery.min.js"));?>"></script>
        
        <!-- Bootstrap Core JavaScript -->
        <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/bootstrap/dist/js/bootstrap.min.js"));?>"></script>

        <!-- Slimscroll JavaScript -->
        <script src="<?php echo urldecode(Url::urlFormat("@static/js/jquery.slimscroll.js"));?>"></script>

        <!-- Sweet-Alert  -->
        <script src="<?php echo urldecode(Url::urlFormat("@static/vendors/sweetalert/dist/sweetalert.min.js"));?>"></script>

        <!-- Init JavaScript -->
        <script src="<?php echo urldecode(Url::urlFormat("@static/js/init.js"));?>"></script>
    </body>
</html>
 <!-- Powered by baxgsun@163.com -->