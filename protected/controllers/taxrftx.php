<?php
//企业退税申请
class TaxrftxController extends TaxHandle
{
	public $attachType=2;
    public function init() {
        parent::init();
        $this->entity=new RefundtaxEntity();
    }
}
