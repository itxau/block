<?php
$content=$_GET['content'];
//$content='帝国时代';
$content = str_replace(array("\r\n", "\r", "\n"), "", $content);
function curl_post_ner($url, $post_data) {
	$ch = curl_init();
	$header = array("Content-Type" => "application/x-www-form-urlencoded;charset=UTF-8");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //设置HTTP头
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置是否返回信息
	$info = curl_exec($ch); //接收返回信息
	if (curl_errno($ch)) {
		print curl_error($ch);
	}
	$info = iconv('gb18030', 'utf-8', $info);
	curl_close($ch); //关闭curl链接
	$result = json_decode($info, true);
	return $result;
}
$ner_url = 'http://127.0.0.1:12810/api/gdqhsw/getanswer?talk='.$content.'&user_id=sdfsd&sptag_info=true';
$ner_arr = '{talk:'.$content.',user_id:sdfsd,sptag_info:true}';
$ner_data = curl_post_ner($ner_url, $ner_arr);
//echo $ner_data;
//var_dump($ner_data);
	echo json_encode($ner_data);





?>
