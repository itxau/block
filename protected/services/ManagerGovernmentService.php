<?php
class ManagerGovernmentService
{
    //获得用户的授权政务单位的 ids
    public static function getManagerGovernment($manager_id,$is_admin,$role_id) {

        if ($is_admin) return "";

        $role=(new RoleEntity())->find($role_id);
        if ($role && !$role["is_grantofgov"]) return "";

        $model=new Model("adm_manager_government");
        $objs=$model->fields("gov_id")->where("manager_id=$manager_id")->findAll();

        $ids="-1";
        foreach ($objs as $key => $obj) {
            $ids.=",".$obj["gov_id"];
        }
        return trim($ids,",");
    }

    //获得已经授权的列表
    public static function getGrandedGovernment($manager_id,$page=1) {
        $entity=new ManagerGovernmentEntity();
        return $entity->query(["manager_id"=>$manager_id,"page"=>$page]);
    }

    //获得未授权的列表
    public static function getUngrandGovernment($manager_id,$page=1) {
        $model=new Model("adm_manager_government");
        $objs=$model->fields("gov_id")->where("manager_id=$manager_id")->findAll();

        $ids="";
        foreach ($objs as $key => $obj) {
            $ids.=",".$obj["gov_id"];
        }
        $ids=trim($ids,",");
        $model=new Model("gov_government");
        $where="(`show`=1)";
        if ($ids) $where.="(id not in ($ids))";
        return $model->fields("id,name,full_name")->where($where)->findPage($page);
    }

    //为用户授权
    public static function saveGrandedGovernment($manager_id,$ids) {
    	$entity=new ManagerGovernmentEntity();
    	foreach ($ids as $key => $id) {
    		$data=["manager_id"=>$manager_id,"gov_id"=>$id];
    		$entity->save($data);
    	}
    }
   
}
