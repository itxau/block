<?php
//企业购票申请
class TaxinvoController extends TaxHandle
{
	public $attachType=1;
    public function init() {
        parent::init();
        $this->entity=new BuyinvoiceEntity();
    }
   
}
