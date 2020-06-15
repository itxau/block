<?php
class AttachmentEntity extends BaseEntity
{
    protected $table="blc_attachment";
    protected $table_zh="附件";
    protected $alias="ath";

    protected $hasStatus=false;

    public $typeMap = array(
        "1"=>"购票",
        "2"=>"退税",
        "3"=>"支付备案申请",
        "4"=>"个税申报",     // etp->tax
        "5"=>"所得税优惠",   // etp->gov
        "52"=>"所得税优惠",  // tax->gov
        "6"=>"数据协同申请", // from->assist
        "7"=>"数据协同协助"  // assist->from
    );

    protected $rights = array("insert");

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,doc.address_id,doc.txid,doc.filename,doc.title,doc.filesize,doc.ext";
        $join=" left join blc_document as doc on $this->alias.document_id=doc.id";
        return true;
    }
  
}
