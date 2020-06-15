<?php
class EnterpriseService
{
    public static function updateHeadpic($id,$src) {
        $entity=new EnterpriseEntity();
        $data=array("head_pic"=>$src);
        return $entity->update($id,$data);
    }

    //保存信息
    public static function saveinfo($etp_id) {
        $base=new EnterpriseEntity();
        Req::post("id",$etp_id);
        $base->save(Req::post());
        $entity=new EnterpriseInfoEntity();
        $info=$entity->findWhere("etp_id=$etp_id");
        if ($info)
            $entity->updateWhere("etp_id=$etp_id",Req::post());
        else {
            Req::post("etp_id",$etp_id);
            $model=new Model("etp_enterprise_info");
            $model->data(Req::post())->insert();
        }
    }

    //创建企业区块地址
    public static function createAddress($etp_id) {
        $id=BlcHelper::createAddress();
        if (!$id) {
            return false;
        } else {
            $data=array("address_id"=>$id);
            $entity=new EnterpriseEntity();
            $where="(id=$etp_id)";
            return $entity->updateWhere($where,$data);
        }
    }

    public static function login($account,$password,&$enterprise,&$errmsg) {
    	
    	if (!$account || !$password) {
            $errmsg= "请输入正确的账号与密码！";
    		return false;
    	}

    	$entity=new EnterpriseEntity();
    	$where="(account='$account')";
    	$obj=$entity->findWhere($where);
    	if(!$obj)  {
            $errmsg= "账号错误,请重新输入！";
            return false;
        }

        $validcode = $obj['validcode'];
        if($obj['password']!=Common::generatePWord($password,$validcode)) {
            $errmsg= "密码错误,请重新输入！";
            return false;
        } 

        if($obj["status"]==3) {
            $errmsg= "对不起，此账号已经被禁止登录！";
            return false;
        }

        $ip=Chips::getIP();
        if (isset($obj["white_ips"]) && Validator::ip($ip)) {
            if(strpos($obj["white_ips"],$ip)===false) {
                $errmsg= "非法的IP地址，系统拒绝登录！";
                return false;
            }
        }

        $last_login=date("Y-m-d H:i:s");
        $data=array(
            'last_ip' => $ip, 
            'last_login' => $last_login
        );
        $where="(id=".$obj["id"].")";
        $entity->updateWhere($where,$data);
        $obj["last_login"]=date("Y-m-d H:i:s");
        $obj["last_ip"]=$ip;
        $enterprise=$obj;

        return true;

    }

    //修改密码
    private static function changePwd($id,$oldpasswd,$newpasswd,$repasswd,&$errMsg) {
        if (!$id || !$newpasswd) {
            $errMsg="输入错误，密码修改失败！";
            return false;
        }

        if ($repasswd!=$newpasswd) {
            $errMsg="密码输入不一致，密码修改失败！";
            return false;
        } 
        $entity=new EnterpriseEntity();
        if ($oldpasswd) {
            $obj=$entity->find($id);
            if (!$obj) {
                $errMsg="输入错误，无法修改密码！";
                return false;
            }
            $validcode = $obj['validcode'];
            if($obj['password']!=Common::generatePWord($oldpasswd,$validcode)) {
                $errMsg="旧密码输入错误！";
                return false;
            } 
        }

        $validcode=CHash::random(8);
        $password=Common::generatePWord($newpasswd,$validcode);
        $data=array(
            "password"=>$password,
            "validcode"=>$validcode
        );
        $success=$entity->update($id,$data);
        if (!$success) {
            $errMsg="密码修改失败,请重新再试！";
        }
        return $success;
    }
  
    //管理员修企业户密码
    public static function changeUPwd($id,$newpasswd,$repasswd,&$errMsg) {
        return self::changePwd($id,"",$newpasswd,$repasswd,$errMsg);
    }
    
    //企业修改自己密码
    public static function changeMPwd($id,$oldpasswd,$newpasswd,$repasswd,&$errMsg) {
        return self::changePwd($id,$oldpasswd,$newpasswd,$repasswd,$errMsg);
    }
}
