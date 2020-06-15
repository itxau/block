<?php
class GovernmentService
{

    //获得其他部门列表，即除自己外
    public static function getothers($self_id) {
        $model=new Model("gov_government");
        return $model->fields("id,name,full_name")->where("id<>$self_id")->findAll();

    }

    private static function changePwd($id,$oldpasswd,$newpasswd,$repasswd,&$errMsg) {
        if (!$id || !$newpasswd) {
            $errMsg="输入错误，密码修改失败！";
            return false;
        }

        if ($repasswd!=$newpasswd) {
            $errMsg="密码输入不一致，密码修改失败！";
            return false;
        } 
        $entity=new ManagerEntity();
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
  
    //修改用户密码
    public static function changeUPwd($id,$newpasswd,$repasswd,&$errMsg) {
        return self::changePwd($id,"",$newpasswd,$repasswd,$errMsg);
    }
    
    //修改自己密码
    public static function changeMPwd($id,$oldpasswd,$newpasswd,$repasswd,&$errMsg) {
        return self::changePwd($id,$oldpasswd,$newpasswd,$repasswd,$errMsg);
    }
    
    public static function login($account,$password,$ip,&$government,&$errMsg) {
        $account = Filter::sql($account);
        if(!$account) {
            $errMsg="用户名称不能为空！";
            return false;
        }
        $entity=new GovernmentEntity();
        $obj=$entity->findWhere("account='$account'");
        if(!$obj)  {
            $errMsg="用户账号不存在！";
            return false;
        }

        $validcode = $obj['validcode'];
        if($obj['password']!=Common::generatePWord($password,$validcode)) {
            $errMsg="用户密码错误！";
            return false;
        } 

        if($obj["status"]!=1) {
            $errMsg="账户被禁止登录！";
            return false;
        }

        if ($ip && isset($obj["white_ips"]) && Validator::ip($ip)) {
            if(strpos($obj["white_ips"],$ip)===false) {
                $errMsg= "非法的IP地址，系统拒绝登录！";
                return false;
            }
        }

        $data=array(
            'last_ip' => $ip, 
            'last_login' => date("Y-m-d H:i:s")
        );
        $entity->update($obj['id'],$data);

        $government=$obj;
        return true;
    }

    public static function updateHeadpic($id,$src) {
        $entity=new ManagerEntity();
        $data=array("head_pic"=>$src);
        return $entity->update($id,$data);
    }

    //创建企业区块地址
    public static function createAddress($gov_id) {
        $address_id=BlcHelper::createAddress();
        if (!$address_id) {
            return false;
        } else {
            $data=array("address_id"=>$address_id);
            $entity=new GovernmentEntity();
            $where="(id=$gov_id)";
            return $entity->updateWhere($where,$data);
        }
    }


   
}
