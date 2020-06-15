<?php
/**
*人脸验证信息队列类
**/
class FacequeueService
{
	/**
     *更新已经过期的验证请求
    */
	private static function updateExpired() {
		$entity=new FacequeueEntity();
    	$where="(status=1) and (expired_time<='".date("Y-m-d H:i:s")."')";
    	return $entity->updateWhere($where,["status"=>4]);
	}

    /**
     *查询验证请求信息
     *
     *@param int $type:1:企业请求,2:税务请求,3:政务请求
    */
	public static function queue($type,$user_id){
		$entity=new FacequeueEntity();
		$where="(type=$type) and (user_id=$user_id) and (status=1) and (expired_time>'".date("Y-m-d H:i:s")."')";
		$queue=$entity->findWhere($where);
		if ($queue) {
			return array(
				"id"=>$queue["id"],
				"request_time"=>$queue["request_time"],
				"expired_time"=>$queue["expired_time"]
			);
		} else {
			return false;
		}
	}

	// 有效时间 10 分钟
    /**
     *创建验证请求信息
     *
     *@param int $type:1:企业请求,2:税务请求,3:政务请求
     *@param int $user_id 企业/税务/政务用户ID
     *@param int $expire 默认有效期为10分钟
     *@return bool
    */
    public static function request($type,$user_id,$expire=600) {
    	$entity=new FacequeueEntity();
    	$where="(type=$type) and (user_id=$user_id) and (status=1) and (expired_time>'".date("Y-m-d H:i:s")."')";
    	$queue=$entity->findWhere($where);
    	if ($queue) {
    		return $queue["id"];
    	} else {
    		$data=array(
    			"type"=>$type,
    			"user_id"=>$user_id,
    			"request_time"=>date("Y-m-d H:i:s"),
    			"status"=>1,
    			"expired_time"=>date("Y-m-d H:i:s",time()+$expire)
    		);
    		return $entity->save($data);
    	}
    }

    /**
     *响应验证请求
     *
     *@param int $id:请求ID
     *@param int $status:相应类型：2同意，3拒绝
    */
    public static function response($id,$userid,$status,&$err) {
    	if ($status!=2 && $status!=3) {
            $err="输入的参数错误";
            return false;
        }
    	$entity=new FacequeueEntity();
    	if ($status==2) //同意
    		$where="(id=$id) and (user_id=$userid) and (status=1)";
    	else // 拒绝
    		$where="(id=$id) and (status=1)";
    	$queue=$entity->findWhere($where);
    	if (!$queue) {
            $err="请求信息不存在";
    		return false;
    	} 
        if (strtotime($queue["expired_time"])<time()) {
            $err="请求信息已经过期";
            return false;
        } 
		$data=array(
			"id"=>$id,
			"response_time"=>date("Y-m-d H:i:s"),
			"status"=>$status
		);
        $success=$entity->save($data);
        if ($success) {
            return true;
        } else {
            $err="发生错误，请重新试试";
            return false;
        }
    }

    /**
     *检查是否请求有响应
     *
     *@param int $id:请求ID
     *@return int :1已同意，2：已拒绝，4：未响应，5：已过期，6：不存在， 
    */
    public static function check($id,$userid) {
        $entity=new FacequeueEntity();
        $where="(id=$id) and (user_id=$userid)";
        return $entity->findWhere($where);
    }

    private static function parseData($data) {
        if ($data['haveNext'])
            $next=1;
        else
            $next=0;
        return array('status'=>1,'msg'=>'','havenext'=>$next,'data'=>$data['data']);
    }

    //公告信息
    public static function announcement($scope,$page=1) {
        $model=new Model("adm_announce");
        $fields="title,url,created_time";
        $where="(status=1) and (scope<>$scope)";
        $announcements=$model->fields($fields)->where($where)->order("created_time desc")->getPage($page,10);
        return self::parseData($announcements);
    }

    //对外支付备案
    public static function payrecord($etp_id,$page=1) {
        $entity=new PayrecordEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["contractno"]=$value["number_h"];
            $record["payamount"]=$value["payamt_h"];
            $record["currency"]=$value["paycurrency_h"];
            $record["created_time"]=$value["created_time"];
            $record["statusname"]=$value["statusname"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //税务申报业务
    public static  function declaretax($etp_id,$page=1){
        $entity=new DeclaretaxEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["taxationname"]=$value["taxationname"];
            $record["tax_period_start"]=$value["tax_period_start"];
            $record["tax_period_end"]=$value["tax_period_end"];
            $record["apply_amount"]=$value["apply_amount"];
            $record["created_time"]=$value["created_time"];
            $record["statusname"]=$value["statusname"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //发票业务
    public static  function invoicetax($etp_id,$page=1){
        $entity=new BuyinvoiceEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["typename"]=$value["buytypename"];
            $record["invoicetype"]=$value["invoicename"];
            $record["num"]=$value["buy_num"];
            $record["created_time"]=$value["created_time"];
            $record["statusname"]=$value["statusname"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //发票业务
    public static  function refundtax($etp_id,$page=1){
        $entity=new RefundtaxEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["taxtype"]=$value["taxationname"];
            $record["tax_period_start"]=$value["tax_period_start"];
            $record["tax_period_end"]=$value["tax_period_end"];
            $record["tax_ticket"]=$value["tax_ticket"];
            $record["amount"]=$value["apply_amount"];
            $record["created_time"]=$value["created_time"];
            $record["statusname"]=$value["statusname"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //产业认定
    public static  function discountax($etp_id,$page=1){
        $entity=new DiscountaxEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["year"]=$value["year"];
            $record["created_time"]=$value["created_time"];
            $record["items"]=$value["items"];
            //$record["content"]=$value["content"];
            $record["statusname"]=$value["statusname"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //企业数据
    public static  function documentdata($etp_id,$page=1){
        $entity=new FinancialdocsEntity();
        $records=$entity->query(["etp_id"=>$etp_id,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["year"]=$value["year"];
            $record["month"]=$value["month"];
            $record["doctype"]=$value["doctype_name"];
            $record["reporttype"]=$value["reptype_name"];
            $record["created_time"]=$value["created_time"];
            $record["txid"]=$value["txid"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }

    //视频会员
    public static  function videomeeting($type,$page=1){
        $entity=new MeetingEntity();
        $records=$entity->query(["type"=>$type,"status"=>1,"page"=>$page]);
        $record=array();
        foreach ($records["data"] as $key => $value) {
            $record["title"]=$value["title"];
            $record["room"]=$value["room"];
            $record["auth_url"]=$value["auth_url"];
            $record["anonymous_url"]=$value["anonymous_url"];
            $record["content"]=$value["content"];
            $record["begin_time"]=$value["begin_time"];
            $record["end_time"]=$value["end_time"];
            $records["data"][$key]=$record;
        }
        return self::parseData($records);
    }
    
    
}
