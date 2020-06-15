function getUrlQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return decodeURI(r[2]); return null;
}
function DataLength(fData) 
{ 
    var intLength=0 
    for (var i=0;i<fData.length;i++) 
    { 
        if ((fData.charCodeAt(i) < 0) || (fData.charCodeAt(i) > 255)) 
            intLength++;    
    } 
    return intLength 
}
function common_true(url,params,types){
	var types = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'GET';
var data_list='';
	$.ajax({
	type:types,
	url:url,//硬匹配
	async:true,
	data:params,
	success: function(data){
	data_list=data;
	},
	error:function(data){
		data_list=data;
	}
});
return data_list;
}
 
function common_false(url,params,types){
	
	var types = arguments.length > 1 && arguments[2] !== undefined ? arguments[2] : 'GET';
	var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
var data_list='';
	$.ajax({
	type:types,
	url:url,//硬匹配
	async:false,
	data:params,
	success: function(data){
		data_list=data;
	},
	error:function(data){
		data_list=data;
	}
});
return data_list;
}