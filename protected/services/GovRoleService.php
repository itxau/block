<?php
class GovRoleService
{
    //获得角色权限
    public static function getModeleRights($gov_id,$module) {
        $right=new GovRightEntity();
        return $right->queryAll(["eq__gov_id"=>$gov_id,"eq__module"=>$module],"sort");
    } 

    //获得模块列表
    public static function getModules($gov_id) {
        $module=new GovModuleEntity();
        $modules=$module->queryAll(array("eq__gov_id"=>$gov_id),"sort");
        $result=array();
        foreach ($modules as $key => $value) {
            $result[$value['code']]=$value['name'];
        }
        return $result;
    } 
      
    public static function roleMap($gov_id,int $status) {
        $conditions["gov_id"]=$gov_id;
        if ($status==1) {
            $conditions["status"]=1;
        } elseif($status==2) {
            $conditions["ne_status"]=1;
        } 

        $entity=new GovRoleEntity();
        $depts=$entity->queryAll($conditions,"name");
        $map=array();
        foreach ($depts as $key => $dept) {
            $map[$dept['id']]=$dept['name'];
        }
        return $map;
    }
   
}
