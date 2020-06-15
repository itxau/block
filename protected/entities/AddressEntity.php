<?php
class AddressEntity extends BaseEntity
{
    protected $table="blc_address";
    protected $table_zh="区块地址";
    protected $alias="adr";
    
    //地址类别
    public $typeMap=array(
    	"1"=>"企业",
    	"2"=>"政务",
    	"3"=>"税务",
    	"4"=>"皓风"
    );

    protected $hasStatus=false;

    //操作权限
    protected $rights = array("insert");

}
