<?php
//企业所得税申报
class TaxdltxController extends TaxHandle
{
	public $attachType=4;
    public function init() {
        parent::init();
        $this->entity=new DeclaretaxEntity();
    }

}
