<?php
class DatashareEntity extends BaseEntity
{
    protected $table="gov_datashare";
    protected $table_zh="数据协同";
    protected $alias="dtx";

    //状态列表
    public $statusMap = array (
        "1"=>"草稿",
        "2"=>"已提交",
        "3"=>"已受理",
        "4"=>"已拒绝"
    );

    public $lableMap = array(
        "1"=>"default",
        "2"=>"primary",
        "3"=>"success",
        "4"=>"danger",
        "5"=>"info",
        "6"=>"warning"
    );

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,frm.name as frmname,frm.full_name as frmfullname,ait.name as aitname,ait.full_name as aitfullname";
        $join=" inner join gov_government as frm on $this->alias.from_id=frm.id";
        $join.=" inner join gov_government as ait on $this->alias.assist_id=ait.id";
        return true;
    }
    
              

}
