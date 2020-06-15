<?php
//手机验证码
class MobileCodeEntity extends BaseEntity
{
    protected $table="adm_mobile_code";
    protected $table_zh="手机验证码";

    protected $rights = array("insert","delete","deleteWhere");

    protected $hasStatus=false;

}
