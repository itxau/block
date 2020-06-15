<?php
class GovDatahistoryEntity extends BaseEntity
{
    protected $table="gov_datahistory";
    protected $table_zh="区块链文档";
    protected $alias="tds";

    protected $rights = array("insert","changestatus");

    public $export_fields = array();

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,gov.name govname,gov.full_name,doc.address_id,doc.title,doc.txid,doc.filename,doc.filesize,doc.mimetype,doc.ext";
        $join=" inner join gov_government as gov on $this->alias.gov_id=gov.id";
        $join.=" inner join blc_document as doc on $this->alias.document_id=doc.id";
     
        return true;
    }

   

    
}
