<?php
//企业对外支付备案
class TaxrecoController extends TaxHandle
{
	public $attachType=3; //附件文档值
    public function init() {
        parent::init();
        $this->entity=new PayrecordEntity();
    }
 
}
