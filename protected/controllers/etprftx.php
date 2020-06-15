<?php
//企业退税申请
class EtprftxController extends EtpApply
{
    public $attachType=2; //附件文档值

    public function init() {
        parent::init();
        $this->entity=new RefundtaxEntity();
    }
}
