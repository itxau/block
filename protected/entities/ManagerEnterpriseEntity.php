<?php
class ManagerEnterpriseEntity extends BaseEntity
{
    protected $table="adm_manager_enterprise";
    protected $table_zh="用户授权";
    protected $alias="mep";

    protected $hasStatus=false;


    protected $orderSort=" mep.created_time desc ";

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,mgr.name as managername,etp.name as etpname,etp.full_name";
        $join=" left join adm_manager as mgr on $this->alias.manager_id=mgr.id";
        $join.=" left join etp_enterprise as etp on $this->alias.etp_id=etp.id";
        return true;
    }

  
}
