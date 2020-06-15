<?php
/**
* 企业15%所得税优惠申请  企业->税务局->管里局
**/
class EtpdstxController extends EtpApply
{
    public $attachType=5; //附件文档值

    public function init() {
        parent::init();
        $this->entity=new DiscountaxEntity();
    }
    public function distount(){
        $this->layout="";
       
        $entity=new  DistountEntity();
        $data=$entity->distount(); 
        $this->assign("data",$data);
        $this->redirect();
    }
   
    
}
