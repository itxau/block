<?php
//公告通知
class GovannoController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new AnnounceEntity();
    }

    public function index() {
        Req::post("eq__status",1);
        Req::post("ne__scope",2);
        $data=$this->entity->query(Req::args());
        $this->assign("data",$data);
        $this->redirect();
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
