<?php
class GovDeptService
{
    public static function deptMap($gov_id,int $status) {
        $conditions["gov_id"]=$gov_id;
        if ($status==1) {
            $conditions["status"]=1;
        } elseif($status==2) {
            $conditions["ne_status"]=1;
        } 

        $entity=new GovDeptEntity();
        $depts=$entity->queryAll($conditions,"name");
        $map=array();
        foreach ($depts as $key => $dept) {
            $map[$dept['id']]=$dept['name'];
        }
        return $map;
    }
   
}
