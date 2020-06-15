<?php
class FacequeueEntity extends BaseEntity
{
    protected $table="adm_facequeue";
    protected $table_zh="人脸验证信息队列";
  
    public $typeMap = array (
        "1"=>"企业用户",
        "2"=>"税务用户",
        "3"=>"政务用户"
    );

    protected $orderSort=" request_time desc ";
      
    protected $hasStatus=false;

}
