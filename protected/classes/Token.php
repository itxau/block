<?php
/**
 * 获得 access token
 *
 * @author sun xian gen
 */
class Token{

	public static function create($app_id,$app_secret,$delay=2) {
        $entity=new ApiEntity();
		$where="(app_id='$app_id') and (app_secret='$app_secret') and (status=1)";
        $obj=$entity->findWhere($where);
		if (!$obj) {
			return array("status"=>0,"msg"=>"APPID没有开通或已停止接入！","data"=>null);
		}

        $skey  = CHash::random(8,'int');
        $token = CHash::md5(time(),$skey);
      
        $data=array(
            "api_id"=>$obj["id"],
            "token"=>$token,
            "expire_time"=>date('Y-m-d H:i:s',strtotime('+'.$delay.' hour')),
            "status"=>1
        );
        $entity=new AccessTokenEntity();
        $success=$entity->save($data);

        if ($success) {
            unset($data["api_id"]);
            unset($data["status"]);
        	return array("status"=>1,"msg"=>"","data"=>$data);
        }
        else {
            return array("status"=>0,"msg"=>"请求失败，请重新请求一次!","data"=>null);
        }
    }

   
    public static function get($token)  {
    	$entity=new AccessTokenEntity();
        $where="(token='$token') and (status=1)";
        $obj=$entity->findWhere($where);
        if ($obj) {
            if (time()<strtotime($obj["expire_time"]))
                return true;
            else 
                return array('status'=>0,'msg'=>'登录已超时，您必须重新登录','data'=>null);
        } else {
            return array('status'=>0,'msg'=>'token不正确，无法执行请求','data'=>null);
        }
    }
  
	
}
