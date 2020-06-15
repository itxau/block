<?php
$keyword=$_GET['keyword'];
$type=$_GET['type'];
 //$keyword='纳税人';
 //$type='hardMatch';
//$keyword='我集团是一家综合性集团公司，下属各公司分别经营石油化工、房地产开发、建筑施工、商贸、物流等。为支援新冠肺炎疫情防控工作，近日集团公司拟向市慈善总会捐赠一批汽油，用于防疫车辆使用，可以享受免征增值税和消费税优惠吗';

function get_es_data($query,$es){
	$header = array("Content-Type:application/json;charset=utf-8");
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //设置HTTP头
    curl_setopt($ch, CURLOPT_URL, $es);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置是否返回信息
    $info = curl_exec($ch); //接收返回信息
    if (curl_errno($ch)) {
        print curl_error($ch);
    }
    curl_close($ch); //关闭curl链接
    $arr = json_decode($info, 1);
	return $arr;
}
switch ($type){
	case 'hardMatch'://硬匹配
	$ner_url = '127.0.0.1:9200/rs_bot_es/_search';
	$ner_arr = '{
	  "query": {
			"bool": {
				"must":
					[
						{"term": {"province": "gd"}},
						{"term": {"type": "qhsw"}},
						{"term": {"question.raw": "'.$keyword.'"}}
					]
			}
		}
	}';
	break;
	case 'esQuestion'://es查询模糊
	$ner_url = '127.0.0.1:9200/rs_bot_tax/_search';
	$ner_arr = '{
  "query": {
    "match": {
      "content": {  
            "query":"'.$keyword.'",  
            "minimum_should_match": "30%"  
        }  
    }
  },
  "highlight": {
    "fields": {
      "content": {
        "type": "plain"
      }
    }
  }
}';
	break;
	case 'allEsQuestion'://es查询模糊
	$ner_url = '127.0.0.1:9200/rs_bot_tax_all/_search';
	$ner_arr = '{
  "query": {
        "multi_match": {
            "query":"'.$keyword.'",
            "type":"best_fields",
            "fields":["title","content"],
            "tie_breaker":0.3,
            "minimum_should_match":"30%"
        }
    }
}';
	break;
}


$ner_data = get_es_data($ner_arr,$ner_url);
//var_dump(count($ner_data['hits']['hits']));
if(count($ner_data['hits']['hits'])==0){
  echo 0;
}else{
   echo '['.json_encode($ner_data,1).']';
} 
 


?>
