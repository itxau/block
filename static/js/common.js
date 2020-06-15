
function doAjaxRequest(url,params,refresh) {
	$.ajax({
		type: 'POST',
		url: url,
		data: params,
		dataType:"json",
		//traditional:true,
		success:function(result){
			if (result["status"]=="success") {
				if (refresh) 
                    location.reload();
                else
                    swal(result["msg"]);
			}
			else {
				swal(result["msg"]);
			}
		},
		error:function(result) {
			swal("出现错误，操作失败.");  
		}
    });
}

function deleteRecord(url,data){
	swal({   
        title: "您确认要删除？",   
        text: "记录被删所选后，数据后无法恢复！",   
        showCancelButton: true,  
        cancelButtonText: "否，取消",   
        confirmButtonColor: "#f60404",   
        confirmButtonText: "是，删除",   
        closeOnConfirm: false 
    }, function(){
    	$.ajax({
    		type: 'POST',
    		url: url,
    		data: data,
    		dataType:"json",
    		success:function(result){
    			if(result["status"]=="success"){
    				swal({   
			            title: result["msg"],
                        timer: 2000, 
			        },function(){
			        	location.reload();
			        });
    			}
    			else {
    				swal(result["msg"]); 
    			}
    		},
    		error:function(result) {
    			swal("出现错误，删除失败."); 
    		}
        });   
    });
}

//统计table中被选择的记录
function idsSelected() {
	var ids=Array();
    $("input[name='selectone[]']:checked").each(function(){
        ids.push($(this).val());
    });
    return ids;
}

function close_pwddialog()  {
    swal('密码修改成功！');
    art.dialog({id: "edit_dialog"}).close();
}

function close_dialog(reload)  {
    art.dialog({id: "edit_dialog"}).close();
    if(reload) page_reload();
}

function open_dialog(url,title,w,h,reload) {
    if(reload)
        art.dialog.open(url,{id:'edit_dialog',title:title,resize:false,width:w,height:h,cancel:function(){page_reload();}});
    else
        art.dialog.open(url,{id:'edit_dialog',title:title,resize:false,width:w,height:h});
}

//刷新
function page_reload(){
    $("#main_form").submit();
}

$(function(){
	var selectall=true;
   
	$("#selectall").on("click",function(){
		if (selectall)
            $("input[name='selectone[]'").prop('checked','checked');
        else
            $("input[name='selectone[]'").removeAttr('checked');
        selectall=!selectall;
    });

    $("#btn_searchId").on("click",function(){
        $("#submittype").val("search");
        $('form:first').submit();
    });

    $("#btn_exportId").on("click",function(){
        $("#submittype").val("export");
        $('form:first').submit();
    });
});
