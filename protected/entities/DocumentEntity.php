<?php
class DocumentEntity extends BaseEntity
{
    protected $table="blc_document";
    protected $table_zh="区块文件";
    protected $alias="doc";

    protected $hasStatus=false;
  
    //操作权限
    protected $rights = array("insert");

}
