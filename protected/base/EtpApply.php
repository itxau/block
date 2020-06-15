<?php
/**
 * 税务申请业务基类
 *
 * @author sun xian gen
 */
class EtpApply extends EtpBase
{
    public $attachType=0; //附件文档值

    //列表信息
    public function index() {
        Req::args("etp_id",$this->enterprise["id"]);
        parent::index();
    }

    //申请
    public function edit() {
        $this->layout="blank";
        $id=Filter::int(Req::args("id"));
        $data=array();
        if ($id) {
            $data=$this->entity->find($id);
        }
        $data["upld"]=Filter::int(Req::args("upld"));
        $this->redirect("edit",false,$data);
    }

    public function attach_upl() {

        $sourceid=Filter::int(Req::args("sourceid"));
        $type=$this->attachType;
        $addressid=$this->enterprise["address_id"];
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
        $con=Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con');
        $this->redirect("/$con/edit/id/$sourceid/upld/1");
    }

    public function save() {
        $this->checkFormToken();
        $data=Req::post();
        $status=Filter::int(Req::post("status"));
        if ($status==2) {
            $data["submit_time"]=date("Y-m-d H:i:s");
        }
        if (!array_key_exists("id", $data)) {
            $data["etp_id"]=$this->enterprise["id"];
            $id=$this->entity->save($data);
        } else {
            $this->entity->save($data);
            $id=$data["id"];
        }
        
        if ($data["status"]==2) {
            echo "<script>parent.close_dialog(true);</script>";
        } else {
            $con=Req::get('con')==null?strtolower(Core::app()->defaultController):Req::get('con');
            $this->redirect("/$con/edit/id/$id/upld/1");
        }
    }

    //修改记录状态
    public function status() {
        $info=["status"=>"fail","msg"=>"对不起，状态修改失败！"];
        echo JSON::encode($info);
    }

}
