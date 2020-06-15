<?php
//企业数据
class EtpdahsController extends EtpBase
{
    public function init() {
        parent::init();
        $this->entity=new FinancialdocsEntity();
    }

    public function index() {
        Req::args("etp_id",$this->enterprise["id"]);
        parent::index();
    }
   
    public function blcup() {
        $this->layout="blank";
        $this->redirect();
    }

    public function blcup_act() {
        $address_id=$this->enterprise["address_id"];
        if (!$address_id) {
            Req::post("errmsg","企业信息不完整！");
            $this->redirect("blcup",true,Req::post());
            die();
        }

        $document_id=DocumentService::upload($address_id,"",$errmsg);
        if (!$document_id) {
            Req::post("errmsg",$errmsg);
            $this->redirect("blcup",true,Req::post());
            die();
        }
    
        $ext=array(
            "etp_id"=>$this->enterprise["id"],
            "document_id"=>$document_id
        );
        $data=array_merge(Req::post(),$ext);
        $success=$this->entity->save($data);
        
        if ($success) {
            echo "<script>parent.close_dialog(true);</script>";
        } else {
            Req::post("errmsg","上链失败，请重新再试！");
            $this->redirect("blcup",true,Req::post());
        }
    }
            
}
