<?php
//企业税务申报
class EtpdltxController extends EtpApply
{
    public $attachType=4; //附件文档值

    public function init() {
        parent::init();
        $this->entity=new DeclaretaxEntity();
    }
    
    
}
