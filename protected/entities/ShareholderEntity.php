<?php
class ShareholderEntity extends BaseEntity
{
    protected $table="etp_shareholder";
    protected $table_zh="企业股东";

     /*股东类型1:自然人股东2:企业股东*/
    private $typeMap=array(
        "1"=>"自然人股东",
        "2"=>"企业股东"
    );
   
     //row数据解析
    protected function parseRow($row) {
        
        $row["typename"]=Common::statusMap($this->typeMap,$row["type"]);
       
        return $row;
    } 

   
}
