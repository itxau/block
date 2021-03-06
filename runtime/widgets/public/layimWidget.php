<link rel="stylesheet" href="<?php echo urldecode(Url::urlFormat("@static/layui/css/layui.css"));?>" media="all">
<script src="<?php echo urldecode(Url::urlFormat("@static/layui/layui.js"));?>"></script>
<style>
	body .layui-layim-min{border:none;}
	#layui-layim-close{line-height: 0px;    margin: 0px;padding: 2px; border:1px solid #198ede;color:#198ede;}
	#layui-layim-close:hover{background: #46dba4;color:#fff;}
	
</style>
<?php $widget = Widget::createWidget('public');$widget->_action = "downWidget";$widget->cache = "false";$widget->run();?> 
<input type="hidden" id="lastid" value="0">
<script type="text/javascript">
var send_type ="<?php echo isset($type)?$type:"";?>";  //发送类型
var uid ="<?php echo isset($uid)?$uid:"";?>";

var search = true;

layui.use('layim', function(layim){
	//基础配置
	layim.config({

		//获取主面板列表信息
		init: {
		  url: "<?php echo urldecode(Url::urlFormat("/publicchat/getList"));?>" //接口地址（返回的数据格式见下文）
		  ,type: 'get' //默认get，一般可不填
		  ,data: {uid:uid,type:send_type} //额外参数
		}
		,uploadFile: {
			url: "<?php echo urldecode(Url::urlFormat("/publicchat/uploadimg/address_id/$address_id"));?>"
		}
		// ,uploadImage: {
		// 	url: "<?php echo urldecode(Url::urlFormat("/publicchat/uploadimg/address_id/$address_id"));?>"
		// }
		//获取群员接口
		,brief: false //是否简约模式（默认false，如果只用到在线客服，且不想显示主面板，可以设置 true）
		,title: '税务在线' //主面板最小化后显示的名称
		,maxLength: 3000 //最长发送的字符长度，默认3000
        ,isfriend: true //是否开启好友（默认true，即开启）
		,isgroup: false //是否开启群组（默认true，即开启）
		,isgovlist:true //自定义拓展第三方好友（默认true，即开启）
		,right: '10px' //默认0px，用于设定主面板右偏移量。该参数可避免遮盖你页面右下角已经的bar。
		//,msgbox: ""
		,chatLog: "<?php echo urldecode(Url::urlFormat("/publicchat/chatlog/uid/$uid/send_type/$type"));?>" //聊天记录地址（如果未填则不显示）
		//,find: "{:url('findgroup/index')}" //查找好友/群的地址（如果未填则不显示）
		,copyright: true //是否授权，如果通过官网捐赠获得LayIM，此处可填true
		,min: true
		,search:search
		,notice:true
		,address_id:"<?php echo isset($address_id)?$address_id:"";?>"   //自定义
		,send_type:send_type   //自定义
		,send_href:"<?php echo urldecode(Url::urlFormat("/publicchat/downfile/address_id/$address_id/txid/"));?>"  //自定义
		,searchurl: "<?php echo urldecode(Url::urlFormat("/publicchat/search"));?>"
		,urlFormat: "<?php echo  Url::getUrlFormat();?>"
		
	});
	
	layim.on('ready', function(res){
		layim.on('sendMessage', function(res){
            // 发送消息
            // var mine = JSON.stringify(res.mine);
			 //var to = JSON.stringify(res.to);
			// console.log(res);return;
			var document_id =0; //是否文件
			$.ajax({
				type: 'POST',
				url: "<?php echo urldecode(Url::urlFormat("/publicchat/sendmessage"));?>",
				data: {
					'send_id':res.mine.id,
					'send_type':send_type,
					'content':res.mine.content,
					'to_id':res.to.id,
					'to_type':res.to.utype,
					'fromid': getType(res.mine.id,res.to.id,res.mine.utype,res.to.utype),
					'document_id':document_id,
					'type':1,
					'fromtype':res.to.type
				},
				dataType:"json",
				success:function(result){
					console.log(result);
				},
				error:function() {
					swal("请求错误！");
				}
			});	
		});
		
		localStorage.clear();
		messageLoad(0);
		layim.on('chatChange', function(res){
			
			var fromid = getType(res.mine.id,res.data.id,res.mine.utype,res.data.utype);
			
				$.ajax({
					type: 'POST',
					url: "<?php echo urldecode(Url::urlFormat("/publicchat/inReadmessage"));?>",
					data: {
						'fromid': fromid,
						'uid': uid,
						'type': send_type,
					},
					dataType:"json",
					success:function(result){
						console.log(result);
					},
					error:function() {
						swal("请求错误！");
					}
				});	
		}); 
	});  

	setInterval(function() {
         messageLoad(1);
	 }, 3000);
	 
	function messageLoad(isCid) {
		$.ajax({
			type: 'GET',
			url: "<?php echo urldecode(Url::urlFormat("/publicchat/getmessage"));?>",
			data: {uid:uid,type:send_type,iscid:isCid,lastid:$("#lastid").val()},
			dataType:"json",
			success:function(result){
				var data =result.data;
				var ismine=false;
				var isID,type ,utype;
				if(data){
					for(var i=0;i<data.length;i++){
						if(data[i].send_id==uid && data[i].send_type==send_type){
							ismine =true;
							isID = data[i].to_id;
							utype = data[i].to_type;
						}else{
							isID = data[i].send_id;
							utype = data[i].send_type;
						}
						type ="friend";
						if(send_type==1){
							if((data[i].send_type==3 && data[i].to_type==1 || data[i].send_type==1 && data[i].to_type==3)){
								type ="govlist";
							}
						}else if(send_type==2 || send_type==3){
							if((data[i].send_type==3 && data[i].to_type==2) || (data[i].send_type==2 && data[i].to_type==3)){
								type ="govlist";
							}
						}
						layim.getMessage({
							username: data[i].username //消息来源用户名
							,avatar: data[i].avatar //消息来源用户头像
							,id: isID //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
							,type: type //聊天窗口来源类型，从发送消息传递的to里面获取
							,content: data[i].content //消息内容
							,cid: isCid //消息id，可不传。除非你要对消息进行一些操作（如撤回）
							,mine: ismine //是否我发送的消息，如果为true，则会显示在右方
							,fromid: data[i].fromid //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
							,timestamp: data[i].time*1000 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
							,utype:utype
						});
						ismine =false;
						$("#lastid").val(data[i].id);
					}
				}
			},
			error:function() {
				
			}
		});
	}
});  

//console.log(parent.layui.layim.cache().local.chatlog); 
function getType(nid,tid,m_type,t_type){
	if(m_type==1){
		return nid+'0'+m_type+t_type+'0'+tid;
	}else if(m_type==2){
		if(t_type==1){
			return tid+'0'+t_type+m_type+'0'+nid;
		}else{
			return nid+'0'+m_type+t_type+'0'+tid;
		}
	}else{
		return tid+'0'+t_type+m_type+'0'+nid;
	}
}

function show(){
      window.open("http://183.60.4.181/h5tax/demo.html?"+Date.parse(new Date()),'online');
    }   	
</script>

<link type="text/css" rel="Stylesheet" href="<?php echo urldecode(Url::urlFormat("@static/layui/css/common.css"));?>">
<div class="ce_nav">
<ul style="height: auto;">
	<?php if($type==1){?>
	<li>
		<a target="_blank" onclick="show()" href="javascript:;">
			<div class="siteStar-font ceNav-icon" style="font-size: 30px;"></div>
			<span>在线客服</span>
		</a>
	</li>
	<?php }?>
	<li style="height: 56px;">
	
	</li> 
</ul>
<div class="clear"></div>
</div>



 <!-- Powered by baxgsun@163.com -->