<?php
class GovDeptEntity extends BaseEntity
{
    protected $table="gov_dept";
    protected $table_zh="部门";
   

    public function deleteValidator($ids){

    	if (!parent::deleteValidator($ids)) return false;

    	$success=$this->parseIds($ids,$where,"dept_id");
    	if (!$success) return false;

    	$entity=new GovUserEntity();
    	$obj=$entity->findWhere($where,"id");
    	return empty($obj);

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
        $gov_id=Filter::int(Req::post("gov_id"));
    	$where="(id<>$id) and (gov_id=$gov_id) and (name='$name')";
    	$obj=$this->findWhere($where,"id");
    	if ($obj) {
    		$errmsg="部门名称 '$name' 已经被使用！";
    		return false;
    	}
    	$extend["name"]=$name;

        return true;
    }
    
}
