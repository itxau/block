<?php
//用户管理
class EtpmeetController extends EtpBase
{
    public function init() {
        parent::init();
        $this->entity=new MeetingEntity();
    }

    //列表信息
    public function index() {
        Req::args("type",1);
        Req::args("status",1);
        Req::args("lt__begin_time",date("Y-m-d:H:i:s"));
        Req::args("gt__end_time",date("Y-m-d:H:i:s"));
        parent::index();
    }

    //删除记录
    public function delete() {
        $info=["status"=>"fail","msg"=>"对不起，你无权记此录删！"];
        echo JSON::encode($info);
    }

    //修改记录状态
    public function status() {
        $info=["status"=>"fail","msg"=>"对不起，状态修改失败！"];
        echo JSON::encode($info);
    }

}
