<?php
class ManagerEnterpriseService
{
    //获得用户的授权政务单位的 ids
    public static function getManagerEnterprise($manager_id,$is_admin,$role_id) {

        if ($is_admin) return "";

        $role=(new RoleEntity())->find($role_id);
        if ($role && !$role["is_grantofetp"]) return "";

        $model=new Model("adm_manager_enterprise");
        $objs=$model->fields("etp_id")->where("manager_id=$manager_id")->findAll();

        $ids="-1";
        foreach ($objs as $key => $obj) {
            $ids.=",".$obj["etp_id"];
        }
        return trim($ids,",");
    }
   
    //获得已经授权的企业列表
    public static function getGrantedEnterprise($manager_id,$page=1) {
        $entity=new ManagerEnterpriseEntity();
        return $entity->query(["manager_id"=>$manager_id,"page"=>$page]);
    }

    //获得未授权的企业列表
    public static function getUngrantEnterprise($manager_id,$page=1) {
        $model=new Model("adm_manager_enterprise");
        $objs=$model->fields("etp_id")->where("manager_id=$manager_id")->findAll();

        $ids="";
        foreach ($objs as $key => $obj) {
            $ids.=",".$obj["etp_id"];
        }
        $ids=trim($ids,",");
        $model=new Model("etp_enterprise");
        $where="(1=1)";
        if ($ids) $where="(id not in ($ids))";
        return $model->fields("id,name,full_name")->where($where)->findPage($page);
    }

    //用户授权企业
    public static function saveGrantedEnterprise($manager_id,$ids) {
    	$entity=new ManagerEnterpriseEntity();
    	foreach ($ids as $key => $id) {
    		$data=["manager_id"=>$manager_id,"etp_id"=>$id];
    		$entity->save($data);
    	}
    }
   
}
