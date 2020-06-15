<?php
class GovUserService
{
  
    private static function changePwd($id,$oldpasswd,$newpasswd,$repasswd,&$errMsg) {
        if (!$id || !$newpasswd) {
            $errMsg="输入错误，密码修改失败！";
            return false;
        }
        if ($repasswd!=$newpasswd) {
            $errMsg="密码输入不一致，密码修改失败！";
            return false;
        } 
        $entity=new GovUserEntity();
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
    
    public static function login($account,$name,$password,$ip,&$govuser,&$errMsg) {
        $account = Filter::sql($account);
        if(!$account) {
            $errMsg="用户名称不能为空！";
            return false;
        }
        $entity=new GovernmentEntity();
        $gov=$entity->findWhere("account='$account'");
        if(!$gov)  {
            $errMsg="账号不存在！";
            return false;
        }

        $gov_id=$gov['id'];

        $entity=new GovUserEntity();
        $user=$entity->findWhere("gov_id=$gov_id and name='$name'");
        if(!$user)  {
            $errMsg="用户不存在！";
            return false;
        }

        $validcode = $user['validcode'];
        if($user['password']!=Common::generatePWord($password,$validcode)) {
            $errMsg="用户密码错误！";
            return false;
        } 

        if($user["status"]!=1) {
            $errMsg="账户被禁止登录！";
            return false;
        }

        if ($ip && isset($user["white_ips"]) && Validator::ip($ip)) {
            if(strpos($user["white_ips"],$ip)===false) {
                $errMsg= "非法的IP地址，系统拒绝登录！";
                return false;
            }
        }

        $data=array(
            'last_ip' => $ip, 
            'last_login' => date("Y-m-d H:i:s")
        );
        $entity->update($user['id'],$data);

        $govuser=$user;
        $govuser["account"]=$account;
        $govuser["govname"]=$gov['name'];
        $govuser['govfullname']=$gov['full_name'];
        $govuser["address_id"]=$gov['address_id'];
        return true;
    }

    public static function updateHeadpic($id,$src) {
        $entity=new GovUserEntity();
        $data=array("head_pic"=>$src);
        return $entity->update($id,$data);
    }

   
}
