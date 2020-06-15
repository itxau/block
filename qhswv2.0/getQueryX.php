<?php
//$_GET["query"]='你好吗';
if($_GET["query"]){
	//$url=' ';
//	if(strpos($_GET["urls"],'demo2.html')!== false){
//		$url='http://111.230.139.145:8088/question_match';
//		$data["query"] = $_GET["query"];
//		$rt1 = postdata($url,$data);
//		$list['xyy']=$rt1;
//	}
//	
//	if(strpos($_GET["urls"],'demo3.html')!== false){
//		$url='http://111.230.139.145:8088/question_match';
//		$data["query"] = $_GET["query"];
//		$rt1 = postdata($url,$data);
//		$list['xyy']=$rt1;
//	}
	//
//	if(strpos($_GET["urls"],'demo.html')!== false){		
	//if($_GET["urls"]=='demo.html'||$_GET["urls"]=='demo.html#'){
//		$url='http://server38.raisound.com:12813/api/fjetc/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true';
//	}
	if(strpos($_GET["urls"],'demo1.html')!== false){
		$url='http://183.60.4.181:12710/api/gdqhsw/getanswer?talk='.$_GET['query'].'&user_id=default';
	}
	if(strpos($_GET["urls"],'demo2.html')!== false){
		$url='http://server38.raisound.com:12813/api/fjetc/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true';
	}
	if(strpos($_GET["urls"],'demo4.html')!== false){
		
		$url='http://183.60.4.181:12720/api/fjetc/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true';
	}
	if(strpos($_GET["urls"],'demo5.html')!== false){
		$url='http://server38.raisound.com:12812/api/gdetc/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true';
	}
	if(strpos($_GET["urls"],'demo6.html')!== false){
		$url='http://183.60.4.181:12711/api/gdqhsw/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true';
	}
	if(strpos($_GET["urls"],'demo.html')!== false){
		$url='http://183.60.4.181:12730/api/gdqhsw/getanswer?talk='.$_GET['query'].'&user_id=sdfsd&sptag_info=true&nsukey=XsuGEp9%2F%2BYFpFsGf6FeV2CITa4JJRiIJZkQHXSo8P8CgFi3bL9%2BkieCJ3QmvXUYtg%2B8xPlNpqBd%2FfDtPLn5nPEIrAAnvIG0JFvM70ZR1h2fM5HEFyCLtxQKg8OJyXT0GZGm6wxBMT%2Bp8m3ZHcyBdhjaZSuwRa52QwxkZpc%2FHd3itTOwSctrqDu0689xYOxAb88Xt0mYXSsbYiIoHJXtxhg%3D%3D';
	}
//	echo $url;
	$rt2 = postdata($url,$data);
	
	$list['dk']=$rt2;
	echo json_encode($list);
}
exit;

/**
 * 通过CURL POST数据
 * @param string $url 请求地址
 * @param string $data post参数
 * @return array   返回结果 
 */
function postdata($url,$data){
  $ch = curl_init();
  ##如果服务器接受json格式参数
  $post_data = json_encode($data);
  $header = array("Content-Type"=>"application/x-www-form-urlencoded","charset"=>"UTF-8");
  curl_setopt($ch, CURLOPT_POST,true);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
  curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
  $info = curl_exec($ch);//接收返回信息
	if(curl_errno($ch)){//出错则显示错误信息
		print curl_error($ch);
	}
	curl_close($ch); //关闭curl链接
  $result = json_decode($info,true);
  return $result;
}
