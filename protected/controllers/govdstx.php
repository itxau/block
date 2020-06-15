<?php
/**
* 企业15%所得税优惠申请  企业->税务局->管里局
**/
class GovdstxController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new DiscountaxEntity();
    }

    public function index() {
        Req::args("ne__status",1);
        $this->reassign();
        $data=$this->entity->query(Req::args());
        $this->assign("data",$data);
        $this->redirect();
    }

    public function handle() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=$this->entity->find($id);
        $this->redirect("handle",false,$data);
    }

    public function save() {
        $this->checkFormToken();
        $data=array(
            "id"=>Req::post("id"),
            "status"=>Req::post("status"),
            "approved_by"=>$this->govuser["id"],
            "approved_note"=>Req::post("approved_note"),
            "approved_time"=>date("Y-m-d H:i:s")
        );
        $this->entity->save($data);
        echo "<script>parent.close_dialog(true);</script>";
    }

    
    public function delete() {
        echo JSON::encode(["status"=>"fail","msg"=>"没有该项操作权限！"]);
    }

    public function status() {
        echo JSON::encode(["status"=>"fail","msg"=>"没有该项操作权限！"]);
    }

}
