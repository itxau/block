<%@ page language="java" contentType="text/html; charset=UTF-8"
	pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt"%>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<%
	String path = request.getContextPath();
	String basePath = request.getScheme() + "://"
			+ request.getServerName() + ":" + request.getServerPort()
			+ path + "/";
%>

<!DOCTYPE html>
<html>
<head>
<title></title>
<base href="<%=basePath%>">


<style>
        *{
            margin: 0;
            padding: 0;
        }
        html,body{
            width: 100%;
            height: 100%;
        }
        .html-body-overflow
		{
		    overflow-x:hidden;
		    overflow-y:hidden;
		}
        div{
            width:100%;
            height: 100%;
        }
</style>

<script type="text/javascript" src="static/js/face/js/jquery.js"></script>
<script type="text/javascript" src="static/js/easyXDM/easyXDM.min.js"></script>
<script type="text/javascript" src="static/js/zDialog2.3/zDialog.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<input type="hidden" name="HEADPHOTO" id="HEADPHOTO" /> <!-- HEADPHOTO需要调用者赋值 -->
<input type="hidden" name="USERNAME" id="USERNAME" value="${pd.USERNAME }"/>
<div id="container"></div>
<script type="text/javascript">
	var faceResult = "";
	var faceImg = "";
	var faceID = "";
	var password = "";

	//禁止滚动条(默认是没有附加这个样式类的）
	$(document.body).toggleClass("html-body-overflow");
	
	//非IE浏览器本地访问外部需使用https方式,否则不允许使用摄像头
	var path = '<%=basePath%>';
	//var domain = path; //内部使用	
	var domain = "http://www.nfcsjmj.com/ZTCloud/"; //云端测试地址
	var isIE;
	if (path == domain) {
		if ((path.search("http://localhost") != -1) || 
			(path.search("http://192.") != -1)|| 
			(path.search("http://172.") != -1) ||
			(path.search("https://") != -1)) {
			isIE = checkIE();
		} else {
			isIE = true; //固定IE方式
		}
	} else {
		isIE = true; //固定IE方式
	}
	var URL = domain + 'appFaceIDInterface/login_toFaceLogin.do';
	URL += '?USERNAME='+'${pd.USERNAME }';	
	URL += '&AUTHWAY=0'; //0-登录,1-注册
	URL += '&SRC=0'; 	 //0-内部使用,1-区块链使用
	URL += '&isIE=' + isIE;
	URL += '&HEADPHOTO='; 
	
	var socket;
	var generateRpc = function (url) {
		return new easyXDM.Rpc({
			isHost: true,
			remote: URL,
			hash: true,
			protocol: '1',
			container: document.getElementById('container'),
			props: {
				frameBorder: 0,
				scrolling: 'no',
				style: {width: '100%', height: '100%'}
			},
	        onReady: function (msg) {
	        	//AUTHWAY=0且SRC=1时通过此处发送用户头像
	        	var HEADPHOTO = $("#HEADPHOTO").val();
	        	if (HEADPHOTO != '') {	        		
		        	socket.pingIframe($("#HEADPHOTO").val(), function(response){
						//alert(response.msg);
					}, function(errorObj){
						alert('error');
					});
	        	}
	       }
		},
		{
			local: {
				echo: function (message) {
					//alert('main:' + message); //接收处理结果
					//用户名#,#密码(加密后,内部使用)#,#记录ID#,#处理结果(success表示成功)#,#抓拍图像
					var item = message.split("#,#");
					if (item.length == 5) {
						password = item[1];
						faceResult = item[3];
						faceImg = item[4];
						top.Dialog.close();
					}
					return {'msg': 'success'};
				}
			},
			remote: {
				pingIframe: {}
			}
		});
	};
	socket = generateRpc(domain);
	
	function checkIE() {
	    if(!!window.ActiveXObject || "ActiveXObject" in window){
	      return true;
	    }
		return false;
	}
</script>
</body>
</html>