<?php
//用户管理
class TaxmeetController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new MeetingEntity();
    }

}
