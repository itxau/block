<?php
class DistountEntity extends BaseEntity
{
    protected $table="gov_distount_catalog";
	protected $table_zh="企业所得税优惠目录";
	
	public function distount(){
		$entity=new  DistountEntity();
        $data=$entity->queryAll(null,"sort asc"); 
        foreach($data as $val) {
            if($val['parent_id'] == 0) {
                $val['child'] = $this->getChild($val['id'], $data);
                $result[] = $val;
            }
		}
		return $result;
	}
	public function getChild($pid, $data) {
        $result = array();
        foreach($data as $val) {
            if($val['parent_id'] == $pid) {
                $val['child'] = $this->getChild($val['id'], $data);
                $result[] = $val;
            }
        } 
        return $result;
    }  
    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table");
        $fields="id,parent_id,name";
       
        return true;
    } 

}
