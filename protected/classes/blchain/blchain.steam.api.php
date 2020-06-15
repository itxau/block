<?php
class BlcSteamAPI
{
	
	public function listStreamKeyItems() {

	}
	
	public function listStreams($streamName="*") {
		$result=$this->chainMethod("liststreams",$streamName,true);
		return $this->parseResult($result);
	}

	public function setlabel($address,$label) {
		$result=$this->chainMethod('publishfrom',$address,'root','',bin2hex($label));
		return $this->parseResult($result);
	}

	/*
	上传文件
	$address:发布地址
	$stream：存储的流媒体
	$optionKey：存储的键值
	$data：文件流数据
	返回：失败返回 false, 成功发货 文档的 txtid
	*/
	public function publish($address,$stream,$optionKey,$data) {
		$result=$this->chainMethod("publishfrom",$address,$stream,$optionKey,$data);
		if (isset($result["error"]))
			return false;
		else
			return $result["result"];
	}

	//取消订阅
	public function unsubscribe($name) {
		$result = $this->chainMethod("unsubscribe",$name);
		return $this->parseResult($result);
	}

	//订阅
	public function subscribe($name) {
		$result = $this->chainMethod("subscribe",$name);
		return $this->parseResult($result);
	}

	private function parseResult($result) {
		return !isset($result["error"]);
	}

	//$permissions: 权限数字 array(connect, send, receive, issue, create, mine, admin, activate);
	//授权
	public function grant($address,$permissions) {
		$result=$this->chainMethod("grantfrom",BlcConfig::ROOT_ADR,$address,implode(',', $permissions));
		return $this->parseResult($result);
	}

	//取消授权
	public function revoke(string $address,array $permissions) {
		$result=$this->chainMethod("revokefrom",BlcConfig::ROOT_ADR,$address,implode(',', $permissions));
		return $this->parseResult($result);
	}

	//创建流媒体,成功返回 txid
	public function createStream($address,$streamKey) {
		$result=$this->chainMethod("createfrom",$address,"stream",$streamKey,false);
		return $this->parseResult($result);
	}

	//创建区块地址
	public function createAddress(&$address) {
		$result=$this->doPost("getnewaddress");
		if (isset($result["error"])) {
			return false;
		} else {
			$address=$result["result"];
			$permissions=array("create");
            $success = $this->grant($address,$permissions);
            return $success;
		}
	}

	//获取基本信息
	public function getInfo() {
		return $this->doPost("getinfo");
	}
	
	public function chainMethod($method) {
		$args=func_get_args();
		return $this->doPost($method,array_slice($args, 1));
	}
	
	private function doPost($method, $params=array()) {

		$url='http://'.BlcConfig::SERVER_IP.':'.BlcConfig::PORT_NUM.'/';
				
		$payload=json_encode(array(
			'id' => time(),
			'method' => $method,
			'params' => $params,
		));
		
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, BlcConfig::RPC_USER.':'.BlcConfig::RPC_PWD);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($payload)
		));
		
		$response=curl_exec($ch);
				
		$result=json_decode($response, true);
		
		if (!is_array($result)) {
			$info=curl_getinfo($ch);
			$result=array('error' => array(
				'code' => 'HTTP '.$info['http_code'],
				'message' => strip_tags($response).' '.$url
			));
		}
		
		return $result;
	}
}
