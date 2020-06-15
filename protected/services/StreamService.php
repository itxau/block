<?php
class StreamService
{

    public static function createSteam($etp_id,$address,$steamName) {

        $api=new BlcSteamAPI();
        $txid=$api->createStream($address,$steamName);
        if ($txid===false) return false;

        $entity=new StreamEntity();
        $data=array(
            "etp_id"=>$etp_id,
            "streamtxid"=>$txid,
            "streamkey"=>$steamName,
            "status"=>1
        );
        return $entity->save($data);
    }

    public static function login($account,$password,&$enterprise) {
    	
    	if (!$account || !$password) {
    		return array("status"=>0,"msg"=>"输入错误，无法登录！");
    	}

    	$entity=new EnterpriseEntity();
    	$where="(account='$account')";
    	$enterprise=$entity->findWhere($where);
    	if(!$enterprise)  {
            return array("status"=>0,"msg"=>"企业不存在或密码错误！");
        }

        $validcode = $enterprise['validcode'];
        if($enterprise['password']!=Common::generatePWord($password,$validcode)) {
            return array("status"=>0,"msg"=>"登录密码错误！");
        } 

        if($enterprise["status"]!=1) {
            return array("status"=>0,"msg"=>"企业已经被禁止登录！");
        }

        $ip=Chips::getIP();
        if (isset($enterprise["white_ips"]) && Validator::ip($ip)) {
            if(strpos($enterprise["white_ips"],$ip)===false) {
                return array("status"=>0,"msg"=>"非法IP，禁止登录！");
            }
        }

        $last_login=date("Y-m-d H:i:s");
        $data=array(
            'last_ip' => $ip, 
            'last_login' => $last_login
        );
        $where="(id=".$enterprise["id"].")";
        $entity->updateWhere($where,$data);
        $enterprise["last_login"]=date("Y-m-d H:i:s");
        $enterprise["last_ip"]=$ip;

        return true;

    }
}
