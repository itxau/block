<?php
class RefundtaxEntity extends BaseEntity
{
    protected $table="etp_refundtax";
    protected $table_zh="退税申请";
    protected $alias="rta";

    //状态样式
    public $lableMap = array(
        "0"=>"danger",
        "1"=>"default",
        "2"=>"primary",
        "3"=>"warning",
        "4"=>"success",
        "5"=>"info"
    );

    //状态列表
    public $statusMap = array (
        "1"=>"草稿",
        "2"=>"已提交",
        "3"=>"待补资料",
        "4"=>"已受理"
    );

    protected $deleteStatus = "(status<>1)"; //草稿

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,etp.name etpname,etp.full_name,txt.name as taxationname";
        $join=" inner join etp_enterprise as etp on $this->alias.etp_id=etp.id";
        $join.=" inner join adm_taxation as txt on $this->alias.taxation_id=txt.id";
        return true;
    }

  


   
}
