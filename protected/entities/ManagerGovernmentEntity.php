<?php
class ManagerGovernmentEntity extends BaseEntity
{
    protected $table="adm_manager_government";
    protected $table_zh="政务授权";
    protected $alias="mgv";

    protected $hasStatus=false;


    protected $orderSort=" mgv.created_time desc ";

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,mgr.name as managername,gov.name as govname,gov.full_name";
        $join=" left join adm_manager as mgr on $this->alias.manager_id=mgr.id";
        $join.=" left join gov_government as gov on $this->alias.gov_id=gov.id";
        return true;
    }

  
}
