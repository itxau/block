<?php
/**
* 数据协同
**/
class GovdashController extends GovBase
{
    public function init() {
        parent::init();
        $this->entity=new DatashareEntity();
    }

    public function index() {
        $this->reassign();
        Req::args("from_id",$this->gov_id);
        $data=$this->entity->query(Req::args());
        $this->assign("data",$data);
        $this->redirect();
    }

    public function edit() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->findWhere("id=$id and from_id=$this->gov_id");
        }
        $data["upld"]=Filter::int(Req::args("upld"));
        $this->redirect("edit",false,$data);
    }

    public function attach_upl() {

        $sourceid=Filter::int(Req::args("sourceid"));
        $type=Filter::int(Req::args("type"));
        $addressid=$this->govuser["address_id"];
        $title=Filter::sql(Req::args("title"));

        $document_id=DocumentService::upload($addressid,$title,$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("edit",true,Req::post());
            die();
        }

        $data=array("source_id"=>$sourceid,"type"=>$type,"document_id"=>$document_id);

        $entity=new AttachmentEntity();
        $entity->save($data);
        $this->redirect("/govdash/edit/id/$sourceid/upld/1");
    }


    public function save() {
        $this->checkFormToken();
        $data=Req::post();
        $status=Filter::int(Req::post("status"));
        if ($status==2) {
            $data["submit_time"]=date("Y-m-d H:i:s");
        }
        if (!array_key_exists("id", $data)) {
            $data["from_id"]=$this->gov_id;
            $id=$this->entity->save($data);
        } else {
            $id=$data["id"];
            $this->entity->save($data);
        }
        
        if ($data["status"]==2) {
            echo "<script>parent.close_dialog(true);</script>";
        } else {
            $this->redirect("/govdash/edit/id/$id/upld/1");
        }
        
    }

}
