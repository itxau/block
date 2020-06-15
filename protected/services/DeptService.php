<?php
class DeptService
{
    public static function deptMap(int $status) {
        if ($status==1) {
            $conditions=array("status"=>1);
        } elseif($status==2) {
            $conditions=array("ne_status"=>1);
        } else {
            $conditions=array();
        }

        $entity=new DeptEntity();
        $depts=$entity->queryAll($conditions,"name");
        $map=array();
        foreach ($depts as $key => $dept) {
            $map[$dept['id']]=$dept['name'];
        }
        return $map;
    }
   
}
