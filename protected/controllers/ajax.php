<?php

class AjaxController extends Controller
{
    public function list() {
        $id=Req::args("id");
        $entity=new  DistountEntity();
        $id=trim($id,',');
        $data=$entity->queryAll(array("in__id"=>$id)); 
        if ($data){
            foreach($data as $val) {
                if($val['parent_id'] != 0) {
                    $val['parent'] = $this->parentlist($val['parent_id']);
                    $result[] = $val;
                }
            }
            $info=["status"=>"success","msg"=>"","data"=>$result];
        }else{
            $info=["status"=>"fail","msg"=>"数据错误！"];
        }  
       echo JSON::encode($info);
    }
    public function parentlist($id) {
        $entity=new  DistountEntity();
        $data=$entity->queryAll(array("id"=>$id)); 
        foreach($data as $val) {
            if($val['parent_id'] != 0) {
                $val['parent'] = $this->parentlist($val['parent_id']);
                $result[] = $val;
            }else{
                $result[] = $val;
            }
        }
        return $result;   
    }
   
   
}
