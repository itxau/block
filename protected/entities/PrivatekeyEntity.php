<?php
class PrivatekeyEntity extends BaseEntity
{
    protected $table="blc_privatekey";
    protected $table_zh="密钥";
    protected $alias="ptk";

    protected $hasStatus=false;
  
    //操作权限
    protected $rights = array("insert");

}