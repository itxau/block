<?php
class AnnounceEntity extends BaseEntity
{
    protected $table="adm_announce";
    protected $table_zh="通告";

    public $scopeMap = array(
    	"1"=>"所有",
    	"2"=>"企业",
    	"3"=>"政务"
    );

    //row数据解析
    protected function parseRow($row) {
        $row["statusname"]=Common::statusMap($this->statusMap,$row["status"]);
        $row["label"]=Common::statusMap($this->lableMap,$row["status"],"warning");
        $row["scopename"]=Common::statusMap($this->scopeMap,$row["scope"]);
        return $row;
    } 

              

}
