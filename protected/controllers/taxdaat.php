<?php
/**
* 数据协同协助
**/
class TaxdaatController extends TaxBase
{
    public function init() {
        parent::init();
        $this->entity=new DatashareEntity();
    }

    public function index() {
        Req::args("ne__status",1);
        Req::args("assist_id",Constants::TAX_DASH_ID);
        parent::index();
    }

    public function edit() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=$this->entity->find($id);
        $data["upld"]=Filter::int(Req::args("upld"));
        $this->redirect("edit",false,$data);
    }

    public function save() {
        $id=Filter::int(Req::post("id"));
        $status=Filter::int(Req::post("status"));
        $data=array("status"=>$status,"accept_time"=>date("Y-m-d H:i:s"));
        $this->entity->update($id,$data);
        echo "<script>parent.close_dialog(true);</script>";
    }

    public function attach_upl() {

        $sourceid=Filter::int(Req::args("sourceid"));
        $type=Filter::int(Req::args("type"));
        $addressid=Constants::TAX_ADDR_ID;
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
        $this->redirect("/taxdaat/edit/id/$sourceid/upld/1");
    }

    public function delete() {
        echo JSON::encode(["status"=>"fail","msg"=>"没有该项操作权限！"]);
    }

    public function status() {
        echo JSON::encode(["status"=>"fail","msg"=>"没有该项操作权限！"]);
    }

}
