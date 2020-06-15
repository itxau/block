<?php
//用户管理
class GovmeetController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new MeetingEntity();
    }

    //列表信息
    public function index() {
        Req::args("type",2);
        Req::args("status",1);
        Req::args("lt__begin_time",date("Y-m-d:H:i:s"));
        Req::args("gt__end_time",date("Y-m-d:H:i:s"));
        
        $this->reassign();
        $data=$this->entity->query(Req::args());
        
        $this->assign("data",$data);
        $this->redirect();
    }

}
