<?php
class MessageEntity extends BaseEntity
{
    protected $table="adm_message";
    protected $table_zh="互动信息";
    protected $alias="msg";

    protected $hasStatus=false;
  
    //操作权限
    protected $rights = array("insert");

    protected $orderSort=" msg.created_time";

    protected function buildModel(&$model,&$fields,&$join) {
        $model=new Model("$this->table as $this->alias");
        $fields="$this->alias.*,doc.address_id,doc.txid,doc.filename";
        $join=" left join blc_document as doc on $this->alias.document_id=doc.id";
        return true;
    }

}
