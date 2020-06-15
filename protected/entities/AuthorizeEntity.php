<?php
class AuthorizeEntity extends BaseEntity
{
    protected $table="adm_authorize";
    protected $table_zh="授权申请";
    protected $alias="auh";

    //状态样式
    public $lableMap = array(
        "0"=>"default",
        "1"=>"primary",
        "2"=>"warning",
        "3"=>"success",
        "4"=>"danger",
        "5"=>"primary"
    );

    //状态列表
    public $statusMap = array (
        "1"=>"草稿",
        "2"=>"审核中",
        "3"=>"已通过",
        "4"=>"已拒绝"
    );

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,etp.name etpname,etp.full_name";
        $join=" inner join etp_enterprise as etp on $this->alias.etp_id=etp.id";
        
        return true;
    }


      
}
