<?php
class ManagerService
{
    /**
    * 已经授权企业的用户列表
    */
    public static function grantofetpList($deptid=0) {

        //所有需要授权的用户
        $managers=self::managergrant(1,0,intval($deptid));
        $ids="";
        foreach ($managers as $key => $manager) {
            $ids.=",".$manager["id"];
        }
        $ids=trim($ids,",");

        $sql="select mgr.id,mgr.name,mep.num from adm_manager as mgr left join (select manager_id, count(etp_id) as num from adm_manager_enterprise group by manager_id) as mep on mgr.id=mep.manager_id where (1=1)";
        if ($deptid) $sql.=" and (mgr.dept_id=$deptid)";
        if ($ids)    $sql.=" and (mgr.id in ($ids))";
        
        $model=new Model();
        return $model->Query($sql);
    }

    /**
    * 已经授权企业的用户列表
    */
    public static function grantofgovList($deptid=0) {

        //所有需要授权的用户
        $managers=self::managergrant(2,0,intval($deptid));
        $ids="";
        foreach ($managers as $key => $manager) {
            $ids.=",".$manager["id"];
        }
        $ids=trim($ids,",");

        $sql="select mgr.id,mgr.name,mep.num from adm_manager as mgr left join (select manager_id, count(gov_id) as num from adm_manager_government group by manager_id) as mep on mgr.id=mep.manager_id where (1=1)";
        if ($deptid) $sql.=" and (mgr.dept_id=$deptid)";
        if ($ids)    $sql.=" and (mgr.id in ($ids))";
        
        $model=new Model();
        return $model->Query($sql);
    }

    /**
    *需要授权的用户
    *
    *@param int $type: 1:企业，other: 政务
    *@param int $status：1  有效用户，2:被禁止用户，other: 所有用户
    *@param int $deptid: 部门
    **/
    public static function managergrant(int $type,int $status,int $deptid=0) {
        $where=self::grantBuild($type,$status,$deptid);
        $model=new Model("adm_manager");
        return $model->fields("id,name")->where($where)->order("name")->findAll();
    }

    /**
    *需要授权的用户 select Map
    **/
    public static function managergrantMap(int $type,int $status,int $deptid=0) {
        $managers=self::managergrant($type,$status,intval($deptid));
        return self::grantMap($managers);
    }

    //用户授权条件生成
    private static function grantBuild(int $type, int $status,int $deptid=0) {
        $entity=new RoleEntity();
        if ($type==1) { //企业需要授权的角色
            $roles=$entity->queryAll(["is_grantofetp"=>1]);
        } else {  //政务需要授权的角色
            $roles=$entity->queryAll(["is_grantofgov"=>1]);
        }
        $ids="";
        foreach ($roles as $key => $role) {
            $ids.=",".$role["id"];
        }
        $ids=trim($ids,",");
        $where="(1=1)";
        if ($ids) $where.="and (role_id in ($ids))"; //需要授权的角色
        $where.="and (is_admin<>1)"; //超级管理员不需要授权
        if ($deptid) $where.="and (dept_id=$deptid)";

        if ($status==1) {
            $where.="and (status=1)";
        }
        elseif($status==2) {
            $where.="and (status<>1)";
        }
        return $where;
    }

    /**
    * 生成 key->value select
    **/
    private static function grantMap($managers) {
        $map=array();
        foreach ($managers as $key => $manager) {
            $map[$manager['id']]=$manager['name'];
        }
        return $map;
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
    
    public static function login($name,$password,$ip,&$manager,&$errMsg) {
        $name = Filter::sql($name);
        if(!$name) {
            $errMsg="用户名称不能为空！";
            return false;
        }
        $entity=new ManagerEntity();
        $obj=$entity->findWhere("name='$name'");
        if(!$obj)  {
            $errMsg="用户账号不存在！";
            return false;
        }

        $validcode = $obj['validcode'];
        if($obj['password']!=Common::generatePWord($password,$validcode)) {
            $errMsg="密码输入错误！";
            return false;
        } 

        if($obj["status"]!=1) {
            $errMsg="账户已经被禁止登录！";
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

        $manager=$obj;
        return true;
    }

    public static function updateHeadpic($id,$src) {
        $entity=new ManagerEntity();
        $data=array("head_pic"=>$src);
        return $entity->update($id,$data);
    }

   
}
