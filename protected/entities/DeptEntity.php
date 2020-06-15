<?php
class DeptEntity extends BaseEntity
{
    protected $table="adm_dept";
    protected $table_zh="部门";

    public function deleteValidator($ids){

    	if (!parent::deleteValidator($ids)) return false;

    	$success=$this->parseIds($ids,$where,"dept_id");
    	if (!$success) return false;

    	$entity=new ManagerEntity();
    	$obj=$entity->findWhere($where,"id");
    	return empty($obj);

    }

    protected function parseRow($row) {

    	$row=parent::parseRow($row);

    	if (array_key_exists("is_msgofetp", $row) && $row['is_msgofetp']) 
    		$row['msgofetp']='是';
    	else
    		$row['msgofetp']=' 否';
    	if (array_key_exists("is_msgofgov", $row) && $row['is_msgofgov']) 
    		$row['msgofgov']='是';
    	else
    		$row['msgofgov']=' 否';

        return $row;
        
    } 

    public function saveValidator(&$extend,&$errmsg){
    	$id=0;
    	if (array_key_exists("id", Req::post())) {
    		$id=Filter::int(Req::post("id"));
    	}
    	$name=Filter::sql(Req::post("name"));
    	if (!$name) {
    		$errmsg="部门名称输入无效！";
    		return false;
    	}
    	$where="(id<>$id) and (name='$name')";
    	$obj=$this->findWhere($where,"id");
    	if ($obj) {
    		$errmsg="部门名称 '$name' 已经被使用！";
    		return false;
    	}
    	$extend["name"]=$name;

    	if (!array_key_exists("is_msgofetp", Req::post())) {
    		$extend["is_msgofetp"]=0;
    	}
    	if (!array_key_exists("is_msgofgov", Req::post())) {
    		$extend["is_msgofgov"]=0;
    	}
        return true;
    }
    
}
