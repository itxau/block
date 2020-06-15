<?php
//企业购买发票申请
class EtpinvoController extends EtpApply
{
    public $attachType=1; //附件文档值

    public function init() {
        parent::init();
        $this->entity=new BuyinvoiceEntity();
    }
 
}
