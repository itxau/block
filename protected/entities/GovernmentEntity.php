<?php
class GovernmentEntity extends BaseEntity
{
    protected $table="gov_government";
    protected $table_zh="政务机构";
    protected $alias="gov";

    //状态列表
    public $statusMap = array (
        "1"=>"正常",
        "2"=>"审核中",
        "3"=>"已禁止"
    );

    //全部手工开通
    protected $rights = array("edit");

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*";
        $join="";
        return true;
    }
}
