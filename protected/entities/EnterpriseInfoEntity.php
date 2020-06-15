<?php
class EnterpriseInfoEntity extends BaseEntity
{
    protected $table="etp_enterprise_info";
    protected $table_zh="企业详情";
    protected $alias="eti";
    
    protected $hasStatus=false;

    protected $rights = array("insert","edit","changestatus");

}
