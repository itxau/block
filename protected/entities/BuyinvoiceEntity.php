<?php
class BuyinvoiceEntity extends BaseEntity
{
    protected $table="etp_buyinvoice";
    protected $table_zh="购票申请";
    protected $alias="biv";

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

    //状态列表
    public $buytypeMap = array (
        "1"=>"超限量",
        "2"=>"最高开票限额",
        "3"=>"票种核定"
    );

    protected $deleteStatus = "(status<>1)"; //草稿

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,etp.name etpname,etp.full_name,ivt.name as invoicename";
        $join=" inner join etp_enterprise as etp on $this->alias.etp_id=etp.id";
         $join.=" inner join adm_invoicetype as ivt on $this->alias.invoicetype_id=ivt.id";
        return true;
    }


    //row数据解析
    protected function parseRow($row) {
        if (array_key_exists("buy_type",$row) && $this->buytypeMap) {
            $row["buytypename"]=Common::statusMap($this->buytypeMap,$row["buy_type"]);
        }
        if ($this->hasStatus && array_key_exists("status",$row) && $this->statusMap) {
            $row["statusname"]=Common::statusMap($this->statusMap,$row["status"]);
            $row["label"]=Common::statusMap($this->lableMap,$row["status"],"warning");
        }
        
        return $row;
    } 


   
}
